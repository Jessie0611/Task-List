<?php
// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

define('DB_HOST', 'localhost');
define('DB_USER', 'jessie');
define('DB_PASS', 'java123');
define('DB_NAME', 'task');

// Create a MySQLi connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// Set the character set to UTF-8
$conn->set_charset("utf8mb4");

// Check for a connection error
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

?>
