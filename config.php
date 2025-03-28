<?php
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '1111');
define('DB_NAME', 'salon_raya');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// VAPID keys for push notifications
define('VAPID_PUBLIC_KEY', 'BG5UHBERE8s7_dbqGPohOTitg5VbEpC4CWdanwIL0g5AXl_1MjkEPIDmEwF4UnCSEzGiPJ7moFWKjEGzLehH-EM');
define('VAPID_PRIVATE_KEY', 'PnpEpw1WzQBVikCRbM_VQhmtShZ1_--eF84nein21fw');
?> 