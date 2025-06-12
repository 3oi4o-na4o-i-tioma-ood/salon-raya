<?php
require_once 'includes/db_config.php';

echo "<h2>Database Fix Script</h2>";

$conn = getDbConnection();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h3>Step 1: Checking current database structure...</h3>";

// Check if the new columns exist
$result = $conn->query("DESCRIBE reviews");
$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

echo "<p>Current columns: " . implode(', ', $columns) . "</p>";

$needsUpdate = false;
if (!in_array('is_deleted', $columns)) {
    echo "<p style='color: orange;'>Missing column: is_deleted</p>";
    $needsUpdate = true;
}

if (!in_array('deleted_at', $columns)) {
    echo "<p style='color: orange;'>Missing column: deleted_at</p>";
    $needsUpdate = true;
}

if ($needsUpdate) {
    echo "<h3>Step 2: Adding missing columns...</h3>";
    
    // Add is_deleted column
    if (!in_array('is_deleted', $columns)) {
        $sql = "ALTER TABLE reviews ADD COLUMN is_deleted BOOLEAN DEFAULT FALSE";
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>✓ Added is_deleted column</p>";
        } else {
            echo "<p style='color: red;'>✗ Error adding is_deleted: " . $conn->error . "</p>";
        }
    }
    
    // Add deleted_at column
    if (!in_array('deleted_at', $columns)) {
        $sql = "ALTER TABLE reviews ADD COLUMN deleted_at TIMESTAMP NULL";
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>✓ Added deleted_at column</p>";
        } else {
            echo "<p style='color: red;'>✗ Error adding deleted_at: " . $conn->error . "</p>";
        }
    }
    
    // Update existing reviews to not be deleted
    $sql = "UPDATE reviews SET is_deleted = FALSE WHERE is_deleted IS NULL";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>✓ Updated existing reviews</p>";
    } else {
        echo "<p style='color: red;'>✗ Error updating reviews: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: green;'>✓ Database structure is correct</p>";
}

echo "<h3>Step 3: Checking reviews data...</h3>";

// Check total reviews
$result = $conn->query("SELECT COUNT(*) as total FROM reviews");
$total = $result->fetch_assoc()['total'];
echo "<p>Total reviews in database: <strong>$total</strong></p>";

// Check active reviews
$result = $conn->query("SELECT COUNT(*) as active FROM reviews WHERE (is_deleted = 0 OR is_deleted IS NULL)");
$active = $result->fetch_assoc()['active'];
echo "<p>Active reviews: <strong>$active</strong></p>";

// Check main page reviews
$result = $conn->query("SELECT COUNT(*) as main_page FROM reviews WHERE is_on_main_page = 1 AND (is_deleted = 0 OR is_deleted IS NULL)");
$mainPage = $result->fetch_assoc()['main_page'];
echo "<p>Reviews on main page: <strong>$mainPage</strong></p>";

// Check deleted reviews
$result = $conn->query("SELECT COUNT(*) as deleted FROM reviews WHERE is_deleted = 1");
$deleted = $result->fetch_assoc()['deleted'];
echo "<p>Deleted reviews (in trash): <strong>$deleted</strong></p>";

if ($total == 0) {
    echo "<h3>Step 4: No reviews found - Running migration...</h3>";
    
    // Run the migration to add default reviews
    $existingReviews = [
        [
            'client_name' => 'Evelina Evtimova',
            'review_text' => 'Салон Райа е най - доброто място за разкрасяване и добро настроение. Диди е професионалист с петнадесет годишен опит в бранша. Тя е много търпелива с клиентите, винаги усмихната. Аз винаги излизам от там с настроение и със сияйна и блестяща коса! Сърдечно препоръчвам да посетите салона, защото ще останете изключително доволни от постигнатите резултати!',
            'rating' => 5,
            'client_initial' => 'E',
            'background_color' => '#689f38',
            'google_link' => 'https://g.co/kgs/x9ZqR4w',
            'is_on_main_page' => 1
        ],
        [
            'client_name' => 'Cvetelina Mihova',
            'review_text' => 'Това е единствения салон, който посещавам. Наистина професионално обслужване и това е единственото място, където знаят как се работи с къдрава коса. Само от този салон, като изляза не ме е срам от това колко бухнала ми е косата, а се чувствам все едно имам най-красивата коса на света. Диди е най прекрасната фрзьорка! Просто я обичам!',
            'rating' => 5,
            'client_initial' => 'C',
            'background_color' => '#f4511e',
            'google_link' => 'https://g.co/kgs/DtSU4et',
            'is_on_main_page' => 1
        ],
        [
            'client_name' => 'Alexander Penchovski',
            'review_text' => 'Страхотен професионалист и топла атмосфера!',
            'rating' => 5,
            'client_initial' => 'A',
            'background_color' => '#a484e8',
            'google_link' => 'https://g.co/kgs/87FvPtW',
            'is_on_main_page' => 1
        ]
    ];

    $stmt = $conn->prepare("INSERT INTO reviews (client_name, review_text, rating, client_initial, background_color, google_link, is_on_main_page, added_to_main_page_at, is_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, 0)");

    foreach ($existingReviews as $review) {
        $stmt->bind_param("ssisssi", 
            $review['client_name'],
            $review['review_text'],
            $review['rating'],
            $review['client_initial'],
            $review['background_color'],
            $review['google_link'],
            $review['is_on_main_page']
        );
        
        if ($stmt->execute()) {
            echo "<p style='color: green;'>✓ Added: " . htmlspecialchars($review['client_name']) . "</p>";
        } else {
            echo "<p style='color: red;'>✗ Error adding: " . htmlspecialchars($review['client_name']) . "</p>";
        }
    }
}

echo "<h3>✅ Database Fix Complete!</h3>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ul>";
echo "<li>Go to the <a href='worker-dashboard.php' style='color: #a484e8;'>Admin Panel</a></li>";
echo "<li>You should now see reviews in the appropriate tabs</li>";
echo "<li>Check the main page to see if reviews are displaying</li>";
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