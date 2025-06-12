<?php
require_once 'includes/db_config.php';

$conn = getDbConnection();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>Quick Database Test</h2>";

// Check if table exists
$result = $conn->query("SHOW TABLES LIKE 'appointments'");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Appointments table exists</p>";
    
    // Check table structure
    $structure = $conn->query("DESCRIBE appointments");
    echo "<h3>Table Structure:</h3>";
    echo "<ul>";
    while ($row = $structure->fetch_assoc()) {
        echo "<li><strong>{$row['Field']}</strong> - {$row['Type']}</li>";
    }
    echo "</ul>";
    
} else {
    echo "<p style='color: red;'>❌ Appointments table does NOT exist</p>";
    
    // Create the table
    $sql = "CREATE TABLE IF NOT EXISTS appointments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        client_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        email VARCHAR(100) NOT NULL,
        service VARCHAR(255) NOT NULL,
        appointment_date DATE NOT NULL,
        appointment_time TIME NOT NULL,
        comment TEXT,
        duration_minutes INT DEFAULT 60,
        status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
        cancellation_token VARCHAR(64),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>✅ Appointments table created successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating table: " . $conn->error . "</p>";
    }
}

$conn->close();
?>

<style>
body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
</style> 