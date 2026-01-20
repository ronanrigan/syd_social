<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Security: Ensure only admins can delete
if (!isAdmin()) { 
    header("Location: ../login.php"); 
    exit(); 
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 1. First, delete all bookings associated with this activity
    // This prevents database 'Foreign Key' errors
    $del_bookings = $conn->prepare("DELETE FROM bookings WHERE activity_id = ?");
    $del_bookings->bind_param("i", $id);
    $del_bookings->execute();

    // 2. Now delete the activity itself
    $del_activity = $conn->prepare("DELETE FROM activities WHERE activity_id = ?");
    $del_activity->bind_param("i", $id);
    
    if ($del_activity->execute()) {
        header("Location: index.php?deleted=1");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    header("Location: index.php");
}
exit();
?>