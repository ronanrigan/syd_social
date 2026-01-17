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
    <div class="section-header">
        <h2>My Scheduled Activities</h2>
        <p>Manage your upcoming social and wellness events in Sydney.</p>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <p class="alert-success">Successfully booked! We've reserved your spot.</p>
    <?php endif; ?>

    <div class="bookings-list">
        <?php if ($result->num_rows > 0): ?>
            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Category</th>
                        <th>Date & Time</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                            <td><span class="badge"><?php echo $row['category']; ?></span></td>
                            <td><?php echo date('D, M j - g:i a', strtotime($row['activity_date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td>
                                <a href="activity.php?id=<?php echo $row['activity_id']; ?>" class="btn-small">View</a>
                                <a href="api/cancel_booking.php?id=<?php echo $row['booking_id']; ?>" 
                                   class="btn-cancel" 
                                   onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <p>You haven't booked any activities yet.</p>
                <a href="activities.php" class="btn-primary">Explore Activities</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>