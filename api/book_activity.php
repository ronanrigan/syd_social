<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $activity_id = intval($_POST['activity_id']);

    // 1. Start a transaction to ensure data integrity
    $conn->begin_transaction();

    try {
        // 2. Check if slots are available
        $stmt = $conn->prepare("SELECT current_slots FROM activities WHERE activity_id = ? FOR UPDATE");
        $stmt->bind_param("i", $activity_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['current_slots'] > 0) {
            // 3. Record the booking
            $stmt_book = $conn->prepare("INSERT INTO bookings (user_id, activity_id) VALUES (?, ?)");
            $stmt_book->bind_param("ii", $user_id, $activity_id);
            $stmt_book->execute();

            // 4. Decrease the available slots
            $stmt_update = $conn->prepare("UPDATE activities SET current_slots = current_slots - 1 WHERE activity_id = ?");
            $stmt_update->bind_param("i", $activity_id);
            $stmt_update->execute();

            $conn->commit();
            header("Location: ../my_bookings.php?status=success");
        } else {
            $conn->rollback();
            header("Location: ../activity.php?id=$activity_id&status=full");
        }
    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: ../login.php");
}
?>