<?php
// Disable error display (log to file instead)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't output errors to browser

// Create a log file in the same directory
$log_file = __DIR__ . '/appointment.log';

// Log function
function logMessage($message) {
    global $log_file;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
}

// Ensure this file only outputs JSON
header('Content-Type: application/json');

// Log all POST data
logMessage("Received POST request");
logMessage("POST data: " . print_r($_POST, true));

session_start();
require_once 'vendor/autoload.php';
require_once 'includes/email.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Database connection
$conn = new mysqli('localhost', 'root', '', 'salon_raya');
if ($conn->connect_error) {
    logMessage("Connection failed: " . $conn->connect_error);
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");
logMessage("Database connection successful");

// VAPID keys
$vapidKeys = [
    'subject' => 'mailto:arttema9@gmail.com',
    'publicKey' => 'BG5UHBERE8s7_dbqGPohOTitg5VbEpC4CWdanwIL0g5AXl_1MjkEPIDmEwF4UnCSEzGiPJ7moFWKjEGzLehH-EM',
    'privateKey' => 'PnpEpw1WzQBVikCRbM_VQhmtShZ1_--eF84nein21fw'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $client_name = $_POST['client_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $service = $_POST['service'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';

    // Log the processed data
    logMessage("Processed form data:");
    logMessage("Name: $client_name");
    logMessage("Phone: $phone");
    logMessage("Email: $email");
    logMessage("Service: $service");
    logMessage("Date: $appointment_date");
    logMessage("Time: $appointment_time");
    logMessage("Comment: $comment");

    // Validate required fields
    if (empty($client_name) || empty($phone) || empty($email) || empty($service) || empty($appointment_date) || empty($appointment_time)) {
        logMessage("Missing required fields");
        die(json_encode(['success' => false, 'message' => 'All required fields must be filled']));
    }

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO appointments (client_name, phone, email, service, appointment_date, appointment_time, comment) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        logMessage("Prepare failed: " . $conn->error);
        die(json_encode(['success' => false, 'message' => 'Database prepare failed']));
    }

    $stmt->bind_param("sssssss", $client_name, $phone, $email, $service, $appointment_date, $appointment_time, $comment);

    if ($stmt->execute()) {
        logMessage("Appointment saved successfully for $client_name");
        
        // Get the appointment ID
        $appointmentId = $conn->insert_id;
        
        // Add to Google Calendar
        $calendar_result = addToGoogleCalendar($client_name, $service, $appointment_date, $appointment_time, $comment, $appointmentId);
        if ($calendar_result) {
            logMessage("Appointment added to Google Calendar successfully");
        }
        
        // Send push notification
        try {
            // Get all push subscriptions
            $pushStmt = $conn->prepare("SELECT endpoint, p256dh, auth FROM push_subscriptions");
            $pushStmt->execute();
            $pushResult = $pushStmt->get_result();
            $subscriptions = $pushResult->fetch_all(MYSQLI_ASSOC);

            logMessage("Found " . count($subscriptions) . " push subscriptions");

            if (!empty($subscriptions)) {
                // Initialize WebPush
                $webPush = new WebPush([
                    'VAPID' => $vapidKeys
                ]);

                // Prepare notification message
                $message = json_encode([
                    'message' => "$client_name резервира $service за " . date('d.m.Y', strtotime($appointment_date)) . " в $appointment_time"
                ]);

                logMessage("Sending push notification with message: " . $message);

                // Send to all subscriptions
                foreach ($subscriptions as $subscription) {
                    try {
                        $report = $webPush->sendOneNotification(
                            Subscription::create([
                                'endpoint' => $subscription['endpoint'],
                                'keys' => [
                                    'p256dh' => $subscription['p256dh'],
                                    'auth' => $subscription['auth']
                                ]
                            ]),
                            $message
                        );

                        // Check if subscription is still valid
                        if ($report->isSubscriptionExpired()) {
                            logMessage("Subscription expired, removing: " . $subscription['endpoint']);
                            // Remove expired subscription
                            $deleteStmt = $conn->prepare("DELETE FROM push_subscriptions WHERE endpoint = ?");
                            $deleteStmt->bind_param("s", $subscription['endpoint']);
                            $deleteStmt->execute();
                        }
                    } catch (Exception $e) {
                        logMessage("Error sending to subscription: " . $e->getMessage());
                    }
                }
            } else {
                logMessage("No push subscriptions found in database");
            }
        } catch (Exception $e) {
            logMessage("Error sending push notification: " . $e->getMessage());
            logMessage("Stack trace: " . $e->getTraceAsString());
        }

        // Send confirmation email
        $emailSent = sendBookingConfirmationEmail(
            $_POST['email'],
            $_POST['client_name'],
            $_POST['service'],
            $_POST['appointment_date'],
            $_POST['appointment_time'],
            $appointmentId,
            $_POST['comment'] ?? ''
        );

        if (!$emailSent) {
            logMessage("Failed to send confirmation email to: " . $_POST['email']);
        }

        echo json_encode(['success' => true, 'message' => 'Appointment saved successfully']);
    } else {
        logMessage("Execute failed: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Error saving appointment: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    logMessage("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
logMessage("Connection closed");

/**
 * Add appointment to Google Calendar
 * 
 * @param string $client_name  Client's name
 * @param string $service      Service name
 * @param string $date         Appointment date (YYYY-MM-DD)
 * @param string $time         Appointment time (HH:MM)
 * @param string $comment      Additional comments (optional)
 * @param int $appointmentId   Appointment ID
 */
function addToGoogleCalendar($client_name, $service, $date, $time, $comment = '', $appointmentId = null) {
    // Path to your Google API credentials JSON file
    $credentialsPath = __DIR__ . '/credentials/google-credentials.json';
    $tokenPath = __DIR__ . '/credentials/google-token.json';
    
    // Skip if credentials file doesn't exist
    if (!file_exists($credentialsPath)) {
        logMessage("Google Calendar credentials file not found: $credentialsPath");
        return false;
    }
    
    // Skip if token file doesn't exist
    if (!file_exists($tokenPath)) {
        logMessage("Google Calendar token file not found: $tokenPath");
        return false;
    }
    
    try {
        // Set a reasonable timeout for API operations
        $original_max_execution_time = ini_get('max_execution_time');
        set_time_limit(60); 
        
        // Create Google API client
        $client = new Google_Client();
        $client->setApplicationName('Salon Raya Appointments');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig($credentialsPath);
        $client->setAccessType('offline');
        
        // Load token
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
        
        // Check token validity
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            } else {
                logMessage("No refresh token available");
                return false;
            }
        }
        
        // Create Calendar service
        $calendarService = new Google_Service_Calendar($client);
        
        // Get primary calendar
        $calendarList = $calendarService->calendarList->listCalendarList();
        $primaryCalendarId = 'primary';
        
        foreach ($calendarList->getItems() as $calendarListEntry) {
            if ($calendarListEntry->getPrimary()) {
                $primaryCalendarId = $calendarListEntry->getId();
                break;
            }
        }
        
        // Format date and time for Google Calendar
        $original_timezone = date_default_timezone_get();
        date_default_timezone_set('Europe/Sofia');
        
        $startDateTime = new DateTime($date . ' ' . $time);
        $endDateTime = clone $startDateTime;
        $endDateTime->add(new DateInterval('PT1H')); // Default 1-hour duration
        
        // Create event
        $event = new Google_Service_Calendar_Event([
            'summary' => "[Salon Raya] $service - $client_name",
            'description' => "Client: $client_name\nService: $service" . ($comment ? "\nComments: $comment" : "") . "\n\nBooking created via Salon Raya booking system.",
            'start' => [
                'dateTime' => $startDateTime->format('c'),
                'timeZone' => 'Europe/Sofia',
            ],
            'end' => [
                'dateTime' => $endDateTime->format('c'),
                'timeZone' => 'Europe/Sofia',
            ],
            'colorId' => '11', // A distinct color (11 is red in Google Calendar)
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'popup', 'minutes' => 30],
                ],
            ],
            'extendedProperties' => [
                'private' => [
                    'createdBy' => 'salon_raya_booking_system',
                    'appointmentId' => $appointmentId ?? '',
                    'serviceType' => $service
                ]
            ]
        ]);
        
        // Restore original timezone
        date_default_timezone_set($original_timezone);
        
        // Insert the event
        try {
            $createdEvent = $calendarService->events->insert($primaryCalendarId, $event);
            
            if ($createdEvent && isset($createdEvent->htmlLink)) {
                logMessage("Event created in Google Calendar: " . $createdEvent->htmlLink);
                set_time_limit($original_max_execution_time);
                return true;
            } else {
                set_time_limit($original_max_execution_time);
                return false;
            }
        } catch (Exception $e) {
            logMessage("Error creating event: " . $e->getMessage());
            set_time_limit($original_max_execution_time);
            return false;
        }
    } catch (Exception $e) {
        logMessage("Google Calendar Error: " . $e->getMessage());
        return false;
    }
}
?> 