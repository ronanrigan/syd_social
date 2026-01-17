<?php
require_once 'config.php';

// Create connection using the constants from config.php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    // In a real project, you might log this to a file, 
    // but for uni, a clear error message is helpful.
    die("Database connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 to handle special characters correctly
$conn->set_charset("utf8mb4");
?>