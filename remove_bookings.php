<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '1111';
$dbname = 'salon_raya';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Delete all records from appointments table
$sql = "DELETE FROM appointments";
if ($conn->query($sql) === TRUE) {
    $affectedRows = $conn->affected_rows;
    echo "Success! All bookings have been removed from the database. ($affectedRows records deleted)";
} else {
    echo "Error: " . $conn->error;
}

// Close the connection
$conn->close();
?> 