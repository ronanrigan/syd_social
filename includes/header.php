<?php 
require_once 'config.php'; 
// Ensure session is started to check roles
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="/Syd_Social/manifest.json">
<meta name="theme-color" content="#007bff">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/Syd_Social/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Sydney Social</div>
            <ul>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-greeting">Hello, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></li>
                    <li><a href="/Syd_Social/admin/index.php">Dashboard</a></li>
                    <li><a href="/Syd_Social/logout.php">Logout</a></li>
                    
                <?php elseif(isset($_SESSION['user_id'])): ?>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="activities.php">Activities</a></li>
                    <li><a href="my_bookings.php">My Bookings</a></li>
                    <li><a href="logout.php">Logout</a></li>
                    
                <?php else: ?>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="activities.php">Activities</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>