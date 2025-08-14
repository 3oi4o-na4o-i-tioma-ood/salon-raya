<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a log file in the same directory
$log_file = __DIR__ . '/appointment.log';

// Log function
function logMessage($message) {
    global $log_file;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
}

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
?> 