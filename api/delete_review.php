<?php
session_start();
require_once '../includes/db.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $review_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    $activity_id = intval($_GET['activity_id']);

    // Ensure the user owns the review before deleting
    $stmt = $conn->prepare("DELETE FROM reviews WHERE review_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $review_id, $user_id);
    
    if ($stmt->execute()) {
        header("Location: ../activity.php?id=$activity_id&deleted=1");
    }
}
?>