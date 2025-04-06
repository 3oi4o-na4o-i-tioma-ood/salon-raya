<?php
require_once 'includes/db_config.php';

header('Content-Type: application/json');

$date = $_GET['date'] ?? null;

if (!$date) {
    echo json_encode([]); // Return empty array if no date provided
    exit();
}

$conn = getDbConnection();
if (!$conn) {
    echo json_encode([]); // Return empty array on DB connection error
    exit();
}

$bookedTimes = [];

// Prepare SQL statement to get booked times for the given date
$stmt = $conn->prepare("SELECT appointment_time FROM appointments WHERE appointment_date = ? AND status != 'cancelled'");

if ($stmt) {
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        // Extract the hour and minute (HH:MM)
        $time = date("H:i", strtotime($row['appointment_time']));
        $bookedTimes[] = $time;
    }
    
    $stmt->close();
} else {
    error_log("Failed to prepare statement in get_booked_times.php: " . $conn->error);
}

$conn->close();

echo json_encode($bookedTimes);
?> 