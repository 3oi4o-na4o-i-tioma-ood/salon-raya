<?php
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'salon_raya');
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}
$conn->set_charset("utf8mb4");

try {
    // Get recent reservations
    $stmt = $conn->prepare("SELECT * FROM appointments ORDER BY created_at DESC LIMIT 10");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $newReservations = $result->fetch_all(MYSQLI_ASSOC);

    // Return the new reservations
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'newReservations' => $newReservations
    ]);
} catch (Exception $e) {
    error_log("Error in check_new_reservations.php: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?> 