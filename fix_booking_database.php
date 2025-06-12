<?php
require_once 'includes/db_config.php';

echo "<h2>Fixing Appointments Table</h2>";

$conn = getDbConnection();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h3>Current Table Structure:</h3>";
$result = $conn->query("DESCRIBE appointments");
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value ?? '') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
}

echo "<h3>Adding Missing Columns...</h3>";

// Add duration_minutes column
$sql = "ALTER TABLE appointments ADD COLUMN IF NOT EXISTS duration_minutes INT DEFAULT 60 AFTER comment";
if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✅ duration_minutes column added/verified</p>";
} else {
    echo "<p style='color: red;'>❌ Error adding duration_minutes: " . $conn->error . "</p>";
}

// Add status column
$sql = "ALTER TABLE appointments ADD COLUMN IF NOT EXISTS status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending' AFTER duration_minutes";
if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✅ status column added/verified</p>";
} else {
    echo "<p style='color: red;'>❌ Error adding status: " . $conn->error . "</p>";
}

// Add cancellation_token column
$sql = "ALTER TABLE appointments ADD COLUMN IF NOT EXISTS cancellation_token VARCHAR(64) AFTER status";
if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✅ cancellation_token column added/verified</p>";
} else {
    echo "<p style='color: red;'>❌ Error adding cancellation_token: " . $conn->error . "</p>";
}

echo "<h3>Updated Table Structure:</h3>";
$result = $conn->query("DESCRIBE appointments");
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value ?? '') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
}

echo "<h3>✅ Database Fix Complete!</h3>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ul>";
echo "<li>Try booking again - the error should be resolved</li>";
echo "<li>Check the <a href='booking.php' style='color: #a484e8;'>Booking Page</a></li>";
echo "<li>Check the <a href='worker-dashboard.php' style='color: #a484e8;'>Admin Panel</a> for appointments</li>";
echo "</ul>";

$conn->close();
?>

<style>
body {
    font-family: 'Montserrat', sans-serif;
    padding: 2rem;
    background-color: #f5f5f5;
    max-width: 1000px;
    margin: 0 auto;
}
h2, h3 {
    color: #333;
}
table {
    border-collapse: collapse;
    margin: 1rem 0;
    background: white;
}
th, td {
    padding: 8px 12px;
    text-align: left;
}
th {
    background-color: #f8f9fa;
    font-weight: 600;
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