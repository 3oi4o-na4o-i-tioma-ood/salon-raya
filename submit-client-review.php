<?php
header('Content-Type: application/json');
require_once 'includes/db_config.php';

// Get database connection
$conn = getDbConnection();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}
$conn->set_charset("utf8mb4");

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

try {
    // Get form data
    $clientName = trim($_POST['client_name'] ?? '');
    $reviewText = trim($_POST['review_text'] ?? '');
    $rating = intval($_POST['rating'] ?? 5);
    
    // Validate required fields
    if (empty($clientName)) {
        echo json_encode(['success' => false, 'message' => 'Моля въведете вашето име.']);
        exit();
    }
    
    if (empty($reviewText)) {
        echo json_encode(['success' => false, 'message' => 'Моля въведете вашия отзив.']);
        exit();
    }
    
    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Моля изберете оценка от 1 до 5 звезди.']);
        exit();
    }
    
    // Sanitize inputs
    $clientName = htmlspecialchars($clientName, ENT_QUOTES, 'UTF-8');
    $reviewText = htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8');
    
    // Generate client initial from name
    $clientInitial = strtoupper(substr($clientName, 0, 1));
    
    // Generate a random background color
    $colors = ['#a484e8', '#689f38', '#f4511e', '#2196f3', '#ff9800', '#9c27b0', '#4caf50', '#e91e63'];
    $backgroundColor = $colors[array_rand($colors)];
    
    // Insert review into database
    $stmt = $conn->prepare("INSERT INTO reviews (client_name, review_text, rating, client_initial, background_color, is_published, is_on_main_page, created_at) VALUES (?, ?, ?, ?, ?, 0, 0, CURRENT_TIMESTAMP)");
    $stmt->bind_param("ssiss", $clientName, $reviewText, $rating, $clientInitial, $backgroundColor);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Благодарим за вашия отзив! Той ще бъде прегледан и публикуван скоро.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Възникна грешка при запазването на отзива.']);
    }
    
} catch (Exception $e) {
    error_log("Error in submit-client-review.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Възникна системна грешка. Моля опитайте отново.']);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?> 