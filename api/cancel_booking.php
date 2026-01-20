<?php
require_once '../includes/db.php';
session_start();

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $booking_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    $redirect_id = isset($_GET['activity_id']) ? intval($_GET['activity_id']) : 0;

    // 1. Fetch activity_id before deleting to update slots
    $stmt = $conn->prepare("SELECT activity_id FROM bookings WHERE booking_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($booking = $result->fetch_assoc()) {
        $activity_id = $booking['activity_id'];

        // 2. Delete the booking record
        $del = $conn->prepare("DELETE FROM bookings WHERE booking_id = ?");
        $del->bind_param("i", $booking_id);
        $del->execute();

        // 3. Increment the available slots back
        $upd = $conn->prepare("UPDATE activities SET current_slots = current_slots + 1 WHERE activity_id = ?");
        $upd->bind_param("i", $activity_id);
        $upd->execute();
    }
}

// Redirect back to the activity page or my bookings
if ($redirect_id > 0) {
    header("Location: ../activity.php?id=$redirect_id");
} else {
    header("Location: ../my_bookings.php");
}
exit();
?>