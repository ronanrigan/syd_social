<?php
require_once 'includes/db.php';
require_once 'includes/auth.php'; // Ensures we can check login status
include 'includes/header.php';

// Redirect to login if not authenticated
requireLogin();

$user_id = $_SESSION['user_id'];

// Fetch bookings for this specific user
$sql = "SELECT b.booking_id, a.title, a.activity_date, a.location, a.category, a.activity_id 
        FROM bookings b 
        JOIN activities a ON b.activity_id = a.activity_id 
        WHERE b.user_id = ? 
        ORDER BY a.activity_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main class="container">
    <div class="bookings-header">
        <h2>My Scheduled Activities</h2>
        <p class="text-light">You have <?php echo $result->num_rows; ?> upcoming events in Sydney.</p>
    </div>

    <div class="bookings-container">
        <?php if ($result->num_rows > 0): ?>
            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>Activity Details</th>
                        <th>Category</th>
                        <th>Schedule</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="activity-name"><?php echo htmlspecialchars($row['title']); ?></div>
                                <div class="booking-loc"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['location']); ?></div>
                            </td>
                            <td><span class="badge"><?php echo $row['category']; ?></span></td>
                            <td>
                                <div class="booking-date"><?php echo date('D, M j', strtotime($row['activity_date'])); ?></div>
                                <div class="text-light" style="font-size: 0.8rem;"><?php echo date('g:i a', strtotime($row['activity_date'])); ?></div>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="activity.php?id=<?php echo $row['activity_id']; ?>" class="btn-view-small">View Details</a>
                                    <a href="api/cancel_booking.php?id=<?php echo $row['booking_id']; ?>" 
                                       class="btn-cancel-small" 
                                       onclick="return confirm('Are you sure you want to cancel?')">Cancel</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="far fa-calendar-times" style="font-size: 3rem; color: #ccc; margin-bottom: 20px;"></i>
                <p>Your schedule is currently empty.</p>
                <a href="activities.php" class="btn-primary" style="margin-top:20px;">Find Something to Do</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>