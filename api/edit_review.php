<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $review_id = intval($_POST['review_id']);
    $user_id = $_SESSION['user_id'];
    $activity_id = intval($_POST['activity_id']);
    $comment = $_POST['comment'];

    // Ensure the user owns the review before updating
    $stmt = $conn->prepare("UPDATE reviews SET comment = ? WHERE review_id = ? AND user_id = ?");
    $stmt->bind_param("sii", $comment, $review_id, $user_id);
    
    if ($stmt->execute()) {
        header("Location: ../activity.php?id=$activity_id&updated=1");
    }
}
?>