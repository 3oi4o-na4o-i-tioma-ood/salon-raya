<?php
// Simplified appointment saving - only database, no email/notifications
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't output errors to browser

// Create a log file in the same directory
$log_file = __DIR__ . '/appointment.log';

// Log function
function logMessage($message) {
    global $log_file;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] SIMPLE: $message\n", FILE_APPEND);
}

// Ensure this file only outputs JSON
header('Content-Type: application/json');

// Log all POST data
logMessage("Received POST request");
logMessage("POST data: " . print_r($_POST, true));

session_start();
require_once 'includes/db_config.php';

// Database connection
$conn = getDbConnection();
if (!$conn) {
    logMessage("Connection failed: " . mysqli_connect_error());
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()]));
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
    $service_details_json = $_POST['service_details'] ?? '[]';

    // Calculate total duration from service_details
    $service_details = json_decode($service_details_json, true);
    $total_duration_minutes = 0;
    if (is_array($service_details)) {
        foreach ($service_details as $item) {
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
    logMessage("Name: $client_name");
    logMessage("Phone: $phone");
    logMessage("Email: $email");
    logMessage("Service: $service");
    logMessage("Date: $appointment_date");
    logMessage("Time: $appointment_time");
    logMessage("Comment: $comment");
    logMessage("Calculated Duration: $total_duration_minutes minutes");

    // Validate required fields
    if (empty($client_name) || empty($phone) || empty($email) || empty($service) || empty($appointment_date) || empty($appointment_time)) {
        logMessage("Missing required fields");
        die(json_encode(['success' => false, 'message' => 'All required fields must be filled']));
    }

    // Generate cancellation token
    $cancellation_token = md5($client_name . $phone . $email . time());
    logMessage("Generated cancellation token: $cancellation_token");

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO appointments (client_name, phone, email, service, appointment_date, appointment_time, comment, duration_minutes, status, cancellation_token) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)");
    if (!$stmt) {
        logMessage("Prepare failed: " . $conn->error);
        die(json_encode(['success' => false, 'message' => 'Database prepare failed: ' . $conn->error]));
    }

    $stmt->bind_param("sssssssis", $client_name, $phone, $email, $service, $appointment_date, $appointment_time, $comment, $total_duration_minutes, $cancellation_token);

    if ($stmt->execute()) {
        $appointmentId = $conn->insert_id;
        logMessage("Appointment saved successfully for $client_name with ID $appointmentId and duration $total_duration_minutes");
        
        // Success response
        echo json_encode([
            'success' => true, 
            'message' => 'Appointment booked successfully!',
            'appointment_id' => $appointmentId,
            'redirect' => 'booking-confirmation.php'
        ]);
        
    } else {
        logMessage("Execute failed: " . $stmt->error);
        die(json_encode(['success' => false, 'message' => 'Failed to save appointment: ' . $stmt->error]));
    }
    
    $stmt->close();
} else {
    logMessage("Invalid request method");
    die(json_encode(['success' => false, 'message' => 'Invalid request method']));
}

$conn->close();
logMessage("Script completed successfully");
?> 