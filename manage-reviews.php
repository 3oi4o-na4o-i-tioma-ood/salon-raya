<?php
session_start();
require_once 'includes/db_config.php';

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get database connection
$conn = getDbConnection();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}
$conn->set_charset("utf8mb4");

// Handle different actions
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

switch ($action) {
    case 'get_reviews':
        getReviews($conn);
        break;
    case 'add_to_main_page':
        addToMainPage($conn);
        break;

    case 'delete':
        deleteReview($conn);
        break;
    case 'get_trash':
        getTrashReviews($conn);
        break;
    case 'restore':
        restoreReview($conn);
        break;
    case 'permanent_delete':
        permanentDeleteReview($conn);
        break;
    default:
        // Add new review
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            addReview($conn);
        }
        break;
}

function getReviews($conn) {
    try {
        // Get stored reviews (not on main page and not deleted)
        $storedStmt = $conn->prepare("SELECT * FROM reviews WHERE is_on_main_page = 0 AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY created_at DESC");
        $storedStmt->execute();
        $storedResult = $storedStmt->get_result();
        $storedReviews = $storedResult->fetch_all(MYSQLI_ASSOC);

        // Get main page reviews (not deleted)
        $mainPageStmt = $conn->prepare("SELECT * FROM reviews WHERE is_on_main_page = 1 AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY added_to_main_page_at DESC");
        $mainPageStmt->execute();
        $mainPageResult = $mainPageStmt->get_result();
        $mainPageReviews = $mainPageResult->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'success' => true,
            'stored' => $storedReviews,
            'mainPage' => $mainPageReviews
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error loading reviews: ' . $e->getMessage()]);
    }
}

function addReview($conn) {
    try {
        $clientName = trim($_POST['client_name']);
        $reviewText = trim($_POST['review_text']);
        $rating = intval($_POST['rating']);
        $clientInitial = trim($_POST['client_initial']);
        $backgroundColor = $_POST['background_color'];
        $googleLink = trim($_POST['google_link']);

        // Validate required fields
        if (empty($clientName) || empty($reviewText)) {
            echo json_encode(['success' => false, 'message' => 'Client name and review text are required']);
            return;
        }

        // If no initial provided, use first letter of name
        if (empty($clientInitial)) {
            $clientInitial = strtoupper(substr($clientName, 0, 1));
        }

        $stmt = $conn->prepare("INSERT INTO reviews (client_name, review_text, rating, client_initial, background_color, google_link) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisss", $clientName, $reviewText, $rating, $clientInitial, $backgroundColor, $googleLink);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Review added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error adding review']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function addToMainPage($conn) {
    try {
        $reviewId = intval($_POST['review_id']);
        $stmt = $conn->prepare("UPDATE reviews SET is_on_main_page = 1, added_to_main_page_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->bind_param("i", $reviewId);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Review added to main page successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error adding review to main page']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}



function deleteReview($conn) {
    try {
        $reviewId = intval($_POST['review_id']);
        $stmt = $conn->prepare("UPDATE reviews SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP, is_on_main_page = 0 WHERE id = ?");
        $stmt->bind_param("i", $reviewId);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Review moved to trash']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error moving review to trash']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function getTrashReviews($conn) {
    try {
        $stmt = $conn->prepare("SELECT * FROM reviews WHERE is_deleted = 1 ORDER BY deleted_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $trashReviews = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'success' => true,
            'trash' => $trashReviews
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error loading trash: ' . $e->getMessage()]);
    }
}

function restoreReview($conn) {
    try {
        $reviewId = intval($_POST['review_id']);
        $stmt = $conn->prepare("UPDATE reviews SET is_deleted = 0, deleted_at = NULL WHERE id = ?");
        $stmt->bind_param("i", $reviewId);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Review restored successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error restoring review']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function permanentDeleteReview($conn) {
    try {
        $reviewId = intval($_POST['review_id']);
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ? AND is_deleted = 1");
        $stmt->bind_param("i", $reviewId);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Review permanently deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error permanently deleting review']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

$conn->close();
?> 