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
    file_put_contents($log_file, "[$timestamp] EMAIL: $message\n", FILE_APPEND);
}

// Ensure this file only outputs JSON
header('Content-Type: application/json');

// Log all POST data
logMessage("Received POST request");
logMessage("POST data: " . print_r($_POST, true));

session_start();
require_once 'vendor/autoload.php';
require_once 'includes/db_config.php';
require_once 'includes/email.php';

// Database connection
$conn = getDbConnection();
if (!$conn) {
    logMessage("Connection failed: " . $conn->connect_error);
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");
logMessage("Database connection successful");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $client_name = $_POST['client_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $service = $_POST['service'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    $service_details_json = $_POST['service_details'] ?? '[]'; // Get selected services JSON

    // Calculate total duration from service_details
    $service_details = json_decode($service_details_json, true);
    $total_duration_minutes = 0;
    if (is_array($service_details)) {
        foreach ($service_details as $item) {
            // Handle potential duration ranges (e.g., "40-55") - take the max for safety
            if (isset($item['duration']) && is_string($item['duration']) && strpos($item['duration'], '-') !== false) {
                 $parts = explode('-', $item['duration']);
                 $total_duration_minutes += max(array_map('intval', $parts));
            } elseif (isset($item['duration'])) {
                 $total_duration_minutes += intval($item['duration']);
            }
        }
    }
    // Default to 60 mins if calculation fails or is zero
    if ($total_duration_minutes <= 0) { 
        $total_duration_minutes = 60; 
        logMessage("Warning: Calculated duration was zero or invalid. Defaulting to 60 minutes.");
    }

    // Log the processed data
    logMessage("Processed form data:");
    logMessage("Name: $client_name, Phone: $phone, Email: $email");
    logMessage("Service: $service, Date: $appointment_date, Time: $appointment_time");
    logMessage("Duration: $total_duration_minutes minutes");

    // Validate required fields
    if (empty($client_name) || empty($phone) || empty($email) || empty($service) || empty($appointment_date) || empty($appointment_time)) {
        logMessage("Missing required fields");
        die(json_encode(['success' => false, 'message' => 'All required fields must be filled']));
    }

    // Prepare and execute SQL statement (add duration_minutes)
    $stmt = $conn->prepare("INSERT INTO appointments (client_name, phone, email, service, appointment_date, appointment_time, comment, duration_minutes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        logMessage("Prepare failed: " . $conn->error);
        die(json_encode(['success' => false, 'message' => 'Database prepare failed']));
    }

    $stmt->bind_param("sssssssi", $client_name, $phone, $email, $service, $appointment_date, $appointment_time, $comment, $total_duration_minutes);

    if ($stmt->execute()) {
        // Get the appointment ID
        $appointmentId = $conn->insert_id;
        
        // Generate cancellation token
        $cancellationToken = md5($appointmentId . $email . time());
        logMessage("Generated cancellation token: $cancellationToken");
        
        // Update the appointment with the cancellation token
        $updateStmt = $conn->prepare("UPDATE appointments SET cancellation_token = ? WHERE id = ?");
        if ($updateStmt) {
            $updateStmt->bind_param("si", $cancellationToken, $appointmentId);
            $updateStmt->execute();
            $updateStmt->close();
        }
        
        logMessage("Appointment saved successfully for $client_name with ID $appointmentId and duration $total_duration_minutes");

        // Try to send confirmation email using the shared email function
        try {
            $emailSent = sendBookingConfirmationEmail(
                $email,
                $client_name,
                $service,
                $appointment_date,
                $appointment_time,
                $appointmentId,
                $comment
            );

            if (!$emailSent) {
                logMessage("Warning: Failed to send confirmation email to: " . $email);
            } else {
                logMessage("Confirmation email sent successfully to: " . $email);
            }
        } catch (Exception $e) {
            logMessage("Error sending confirmation email: " . $e->getMessage());
            // Continue anyway - email is optional
        }

        // Return success regardless of email status
        logMessage("Script completed successfully");
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