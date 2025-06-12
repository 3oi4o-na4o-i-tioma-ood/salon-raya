<?php
require_once 'includes/db_config.php';

echo "<h2>Restoring Evelina Evtimova's Review</h2>";

$conn = getDbConnection();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Evelina's original review details
$review = [
    'client_name' => 'Evelina Evtimova',
    'review_text' => 'Салон Райа е най - доброто място за разкрасяване и добро настроение. Диди е професионалист с петнадесет годишен опит в бранша. Тя е много търпелива с клиентите, винаги усмихната. Аз винаги излизам от там с настроение и със сияйна и блестяща коса! Сърдечно препоръчвам да посетите салона, защото ще останете изключително доволни от постигнатите резултати!',
    'rating' => 5,
    'client_initial' => 'E',
    'background_color' => '#689f38',
    'google_link' => 'https://g.co/kgs/x9ZqR4w'
];

echo "<h3>Checking if review already exists...</h3>";

// Check if this review already exists
$checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM reviews WHERE client_name = ?");
$checkStmt->bind_param("s", $review['client_name']);
$checkStmt->execute();
$result = $checkStmt->get_result();
$existingCount = $result->fetch_assoc()['count'];

if ($existingCount > 0) {
    echo "<p style='color: orange;'>⚠️ A review by " . htmlspecialchars($review['client_name']) . " already exists.</p>";
    
    // Show existing review details
    $showStmt = $conn->prepare("SELECT * FROM reviews WHERE client_name = ?");
    $showStmt->bind_param("s", $review['client_name']);
    $showStmt->execute();
    $existing = $showStmt->get_result()->fetch_assoc();
    
    echo "<p><strong>Current status:</strong></p>";
    echo "<ul>";
    echo "<li>On main page: " . ($existing['is_on_main_page'] ? 'Yes' : 'No') . "</li>";
    echo "<li>Deleted: " . ($existing['is_deleted'] ? 'Yes' : 'No') . "</li>";
    echo "</ul>";
    
    if ($existing['is_deleted']) {
        echo "<h3>Restoring from trash...</h3>";
        $restoreStmt = $conn->prepare("UPDATE reviews SET is_deleted = 0, deleted_at = NULL WHERE client_name = ?");
        $restoreStmt->bind_param("s", $review['client_name']);
        
        if ($restoreStmt->execute()) {
            echo "<p style='color: green;'>✅ Review restored from trash!</p>";
        } else {
            echo "<p style='color: red;'>❌ Error restoring review: " . $conn->error . "</p>";
        }
    } else if (!$existing['is_on_main_page']) {
        echo "<h3>Adding to main page...</h3>";
        $mainPageStmt = $conn->prepare("UPDATE reviews SET is_on_main_page = 1, added_to_main_page_at = CURRENT_TIMESTAMP WHERE client_name = ?");
        $mainPageStmt->bind_param("s", $review['client_name']);
        
        if ($mainPageStmt->execute()) {
            echo "<p style='color: green;'>✅ Review added to main page!</p>";
        } else {
            echo "<p style='color: red;'>❌ Error adding to main page: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: green;'>✅ Review already exists and is active on main page!</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Review not found. Adding it back...</p>";
    
    echo "<h3>Adding Evelina's review back to database...</h3>";
    
    $stmt = $conn->prepare("INSERT INTO reviews (client_name, review_text, rating, client_initial, background_color, google_link, is_on_main_page, added_to_main_page_at, is_deleted) VALUES (?, ?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP, 0)");
    
    $stmt->bind_param("ssisss", 
        $review['client_name'],
        $review['review_text'],
        $review['rating'],
        $review['client_initial'],
        $review['background_color'],
        $review['google_link']
    );
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ Successfully restored " . htmlspecialchars($review['client_name']) . "'s review!</p>";
        echo "<p>Review details:</p>";
        echo "<ul>";
        echo "<li><strong>Name:</strong> " . htmlspecialchars($review['client_name']) . "</li>";
        echo "<li><strong>Rating:</strong> " . $review['rating'] . " stars</li>";
        echo "<li><strong>Added to main page:</strong> Yes</li>";
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ Error adding review: " . $conn->error . "</p>";
    }
}

echo "<h3>✅ Restoration Complete!</h3>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ul>";
echo "<li>Check the <a href='index.php' style='color: #a484e8;'>Main Page</a> - Evelina's review should appear in the carousel</li>";
echo "<li>Go to the <a href='worker-dashboard.php' style='color: #a484e8;'>Admin Panel</a> - Check in 'Отзиви на главната страница'</li>";
echo "</ul>";

$conn->close();
?>

<style>
body {
    font-family: 'Montserrat', sans-serif;
    padding: 2rem;
    background-color: #f5f5f5;
    max-width: 800px;
    margin: 0 auto;
}
h2, h3 {
    color: #333;
}
p {
    margin: 0.5rem 0;
}
a {
    color: #a484e8;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
ul {
    margin: 1rem 0;
}
li {
    margin: 0.5rem 0;
}
</style> 