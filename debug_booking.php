<?php
// Debug booking system
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug Booking System</h2>";

// Test 1: Database Connection
echo "<h3>1. Testing Database Connection</h3>";
require_once 'includes/db_config.php';
$conn = getDbConnection();
if ($conn) {
    echo "<p style='color: green;'>✅ Database connection: SUCCESS</p>";
    echo "<p>Database: " . DB_NAME . "</p>";
} else {
    echo "<p style='color: red;'>❌ Database connection: FAILED</p>";
    die();
}

// Test 2: Check appointments table structure
echo "<h3>2. Check Appointments Table</h3>";
$result = $conn->query("DESCRIBE appointments");
if ($result) {
    echo "<table border='1'>";
    echo "<tr><th>Column</th><th>Type</th><th>Default</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Default']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ Table check failed: " . $conn->error . "</p>";
}

// Test 3: Check required columns
echo "<h3>3. Check Required Columns</h3>";
$required_columns = ['client_name', 'phone', 'email', 'service', 'appointment_date', 'appointment_time', 'comment', 'duration_minutes'];
$columns_result = $conn->query("SHOW COLUMNS FROM appointments");
$existing_columns = [];
while ($row = $columns_result->fetch_assoc()) {
    $existing_columns[] = $row['Field'];
}

foreach ($required_columns as $col) {
    if (in_array($col, $existing_columns)) {
        echo "<p style='color: green;'>✅ Column '$col' exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Column '$col' MISSING</p>";
    }
}

// Test 4: Test insert functionality
echo "<h3>4. Test Insert Functionality</h3>";
$test_data = [
    'client_name' => 'Test User',
    'phone' => '0876123456',
    'email' => 'test@example.com',
    'service' => 'Test Service',
    'appointment_date' => '2025-06-15',
    'appointment_time' => '10:00',
    'comment' => 'Test comment',
    'duration_minutes' => 60
];

$stmt = $conn->prepare("INSERT INTO appointments (client_name, phone, email, service, appointment_date, appointment_time, comment, duration_minutes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if ($stmt) {
    echo "<p style='color: green;'>✅ Prepare statement: SUCCESS</p>";
    
    $stmt->bind_param("sssssssi", 
        $test_data['client_name'],
        $test_data['phone'],
        $test_data['email'],
        $test_data['service'],
        $test_data['appointment_date'],
        $test_data['appointment_time'],
        $test_data['comment'],
        $test_data['duration_minutes']
    );
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ Test insert: SUCCESS</p>";
        $test_id = $conn->insert_id;
        echo "<p>Test appointment ID: $test_id</p>";
        
        // Clean up test data
        $conn->query("DELETE FROM appointments WHERE id = $test_id");
        echo "<p>Test data cleaned up</p>";
    } else {
        echo "<p style='color: red;'>❌ Test insert failed: " . $stmt->error . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Prepare statement failed: " . $conn->error . "</p>";
}

// Test 5: Check email configuration
echo "<h3>5. Check Email Configuration</h3>";
if (file_exists('includes/email.php')) {
    echo "<p style='color: green;'>✅ Email file exists</p>";
    require_once 'includes/email.php';
} else {
    echo "<p style='color: red;'>❌ Email file missing</p>";
}

// Test 6: Check vendor/autoload
echo "<h3>6. Check Composer Dependencies</h3>";
if (file_exists('vendor/autoload.php')) {
    echo "<p style='color: green;'>✅ Composer autoload exists</p>";
} else {
    echo "<p style='color: red;'>❌ Composer autoload missing - run 'composer install'</p>";
}

// Test 7: Check recent appointments
echo "<h3>7. Recent Appointments</h3>";
$recent = $conn->query("SELECT * FROM appointments ORDER BY created_at DESC LIMIT 5");
if ($recent && $recent->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Service</th><th>Date</th><th>Time</th><th>Created</th></tr>";
    while ($row = $recent->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['client_name']}</td>";
        echo "<td>{$row['service']}</td>";
        echo "<td>{$row['appointment_date']}</td>";
        echo "<td>{$row['appointment_time']}</td>";
        echo "<td>{$row['created_at']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: orange;'>⚠️ No appointments found in database</p>";
}

echo "<h3>📋 Summary</h3>";
echo "<p>If all tests above show ✅, then the issue might be:</p>";
echo "<ul>";
echo "<li>JavaScript errors on the booking page</li>";
echo "<li>Email sending issues (preventing completion)</li>";
echo "<li>Push notification issues</li>";
echo "<li>Google Calendar integration issues</li>";
echo "</ul>";

echo "<p><a href='booking.php'>Try booking again</a> | <a href='worker-dashboard.php'>Check admin panel</a></p>";

$conn->close();
?>

<style>
body {
    font-family: 'Montserrat', sans-serif;
    padding: 2rem;
    max-width: 1000px;
    margin: 0 auto;
    background: #f5f5f5;
}
table {
    border-collapse: collapse;
    margin: 1rem 0;
    background: white;
}
th, td {
    padding: 8px 12px;
    text-align: left;
    border: 1px solid #ddd;
}
th {
    background-color: #f8f9fa;
}
</style> 