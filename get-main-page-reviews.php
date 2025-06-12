<?php
header('Content-Type: application/json');
require_once 'includes/db_config.php';

$conn = getDbConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'reviews' => []]);
    exit();
}
$conn->set_charset("utf8mb4");

try {
    $stmt = $conn->prepare("SELECT client_name, review_text, rating, client_initial, background_color, google_link FROM reviews WHERE is_on_main_page = 1 AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY added_to_main_page_at DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    $reviews = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'reviews' => $reviews
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'reviews' => []]);
}

$conn->close();
?> 