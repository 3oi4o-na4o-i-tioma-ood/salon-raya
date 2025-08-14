<?php
require_once 'includes/db_config.php';

// Get database connection
$conn = getDbConnection();
if (!$conn) {
    die("Connection failed");
}
$conn->set_charset("utf8mb4");

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

echo "Migrating reviews...<br>";

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
        echo "Added: " . $review['client_name'] . "<br>";
    }
}

echo "Migration complete!<br>";
echo "<a href='worker-dashboard.php'>Go to Admin Panel</a>";

$conn->close();
?>

<style>
body {
    font-family: 'Montserrat', sans-serif;
    padding: 2rem;
    background-color: #f5f5f5;
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