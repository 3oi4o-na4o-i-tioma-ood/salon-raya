<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'u603525247_root');
define('DB_PASS', '#27!VijR');
define('DB_NAME', 'u603525247_salon_raya');

// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'salon_raya');

define('WORKER_PASSWORD_HASH', '2acffe7cb7b378540e80cdaaa6b96cbe9315ef277e0e7c1e41742d95267ee6ef');

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