<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'u603525247_root');
define('DB_PASS', '#27!VijR');
define('DB_NAME', 'u603525247_salon_raya');

// Create database connection
function getDbConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        return false;
    }
    
    return $conn;
}
?> 