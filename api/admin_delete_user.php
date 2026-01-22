<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (!isAdmin()) { die("Unauthorized"); }

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // The database will automatically clear related bookings/reviews 
    // IF you set up "ON DELETE CASCADE" in your SQL exports
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ? AND role = 'user'");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        header("Location: ../admin/users.php?status=success");
    }
}
?>