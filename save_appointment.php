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

// Database connection
$conn = new mysqli('localhost', 'root', '', 'salon_raya');
if ($conn->connect_error) {
    logMessage("Connection failed: " . $conn->connect_error);
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
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