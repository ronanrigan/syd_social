<?php
// Database Credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'sydney_social_hub');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site Settings
define('BASE_URL', 'http://localhost/assignment2/');
define('SITE_NAME', 'Sydney Social Activities Hub');

// Start session globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>