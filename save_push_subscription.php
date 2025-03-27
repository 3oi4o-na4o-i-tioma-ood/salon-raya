<?php
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['endpoint']) || !isset($data['keys']['p256dh']) || !isset($data['keys']['auth'])) {
        throw new Exception('Invalid subscription data');
    }

    $endpoint = $data['endpoint'];
    $p256dh = $data['keys']['p256dh'];
    $auth = $data['keys']['auth'];

    // Check if subscription already exists
    $checkStmt = $conn->prepare("SELECT id FROM push_subscriptions WHERE endpoint = ?");
    $checkStmt->bind_param("s", $endpoint);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing subscription
        $updateStmt = $conn->prepare("UPDATE push_subscriptions SET p256dh = ?, auth = ? WHERE endpoint = ?");
        $updateStmt->bind_param("sss", $p256dh, $auth, $endpoint);
        $success = $updateStmt->execute();
    } else {
        // Insert new subscription
        $insertStmt = $conn->prepare("INSERT INTO push_subscriptions (endpoint, p256dh, auth) VALUES (?, ?, ?)");
        $insertStmt->bind_param("sss", $endpoint, $p256dh, $auth);
        $success = $insertStmt->execute();
    }

    if (!$success) {
        throw new Exception('Failed to save subscription');
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log("Error saving push subscription: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 