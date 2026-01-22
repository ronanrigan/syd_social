<?php
session_start();
require_once '../includes/db.php';

// Security check: Only logged-in users can post reviews
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $activity_id = $_POST['activity_id'];
    $rating = (int)$_POST['rating'];
    $comment = $_POST['comment']; // Prepared statement handles security, no extra function needed

    // Database-driven interaction using Prepared Statements
    $stmt = $conn->prepare("INSERT INTO reviews (activity_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $activity_id, $user_id, $rating, $comment);

    if ($stmt->execute()) {
        // Correcting the redirect to match your activity page
        header("Location: ../activity.php?id=$activity_id&review=success");
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
} else {
    header("Location: ../login.php");
}
?>