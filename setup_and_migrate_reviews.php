<?php
require_once 'includes/db_config.php';

$conn = getDbConnection();
if (!$conn) {
    die("Connection failed");
}
$conn->set_charset("utf8mb4");

echo "<h2>Setting up Reviews System</h2>";

// Step 1: Create the reviews table
echo "<h3>Step 1: Creating reviews table...</h3>";

$createTableSQL = "
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    review_text TEXT NOT NULL,
    rating INT DEFAULT 5 CHECK (rating >= 1 AND rating <= 5),
    client_initial VARCHAR(1) DEFAULT '',
    background_color VARCHAR(20) DEFAULT '#a484e8',
    google_link VARCHAR(255) DEFAULT '',
    is_published BOOLEAN DEFAULT FALSE,
    is_on_main_page BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    added_to_main_page_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
";

if ($conn->query($createTableSQL) === TRUE) {
    echo "<p style='color: green;'>✓ Reviews table created successfully!</p>";
} else {
    echo "<p style='color: red;'>✗ Error creating table: " . $conn->error . "</p>";
    die();
}

// Step 2: Check if reviews already exist
echo "<h3>Step 2: Checking for existing reviews...</h3>";

$checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM reviews");
$checkStmt->execute();
$result = $checkStmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    echo "<p style='color: orange;'>Reviews already exist in database. Skipping migration.</p>";
} else {
    echo "<h3>Step 3: Migrating existing reviews...</h3>";
    
    // Existing reviews from the main page
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

    $stmt = $conn->prepare("INSERT INTO reviews (client_name, review_text, rating, client_initial, background_color, google_link, is_on_main_page, added_to_main_page_at) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");

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

echo "<h3 style='color: green;'>Setup Complete!</h3>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ul>";
echo "<li>Go to the <a href='worker-dashboard.php' style='color: #a484e8;'>Admin Panel</a></li>";
echo "<li>Click on 'Добави отзив в страницата' tab</li>";
echo "<li>You should now see all reviews in 'Отзиви на главната страница'</li>";
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