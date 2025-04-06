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

$bookedIntervals = []; // Array to store [start_minutes, end_minutes] for each booking

// Helper function to convert HH:MM:SS to minutes since midnight
function timeToMinutes($timeStr) {
    if (!$timeStr) return 0;
    $parts = explode(':', $timeStr);
    return intval($parts[0]) * 60 + intval($parts[1]);
}

// Prepare SQL statement to get booked times and durations for the given date
// Default duration_minutes to 60 if it's NULL or 0 for safety
$stmt = $conn->prepare(
    "SELECT appointment_time, COALESCE(NULLIF(duration_minutes, 0), 60) as duration 
     FROM appointments 
     WHERE appointment_date = ? AND status != 'cancelled'"
);

if ($stmt) {
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $startMinutes = timeToMinutes($row['appointment_time']);
        $duration = intval($row['duration']);
        $endMinutes = $startMinutes + $duration;
        
        $bookedIntervals[] = [
            'start' => $startMinutes,
            'end' => $endMinutes
        ];
    }
    
    $stmt->close();
} else {
    error_log("Failed to prepare statement in get_booked_times.php: " . $conn->error);
}

$conn->close();

echo json_encode($bookedIntervals);
?> 