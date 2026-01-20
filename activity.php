<?php
require_once 'includes/db.php';
include 'includes/header.php';

// 1. Get the ID from the URL and sanitize it
$activity_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$is_booked = false; 
$user_booking_id = 0;


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    

    $check_stmt = $conn->prepare("SELECT booking_id FROM bookings WHERE user_id = ? AND activity_id = ?");
    $check_stmt->bind_param("ii", $user_id, $activity_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $is_booked = true; // Variable is now defined as true
        $booking_data = $check_result->fetch_assoc();
        $user_booking_id = $booking_data['booking_id'];
    }
    $check_stmt->close();
}

// 2. Fetch the specific activity
$stmt = $conn->prepare("SELECT * FROM activities WHERE activity_id = ?");
$stmt->bind_param("i", $activity_id);
$stmt->execute();
$result = $stmt->get_result();
$activity = $result->fetch_assoc();

if (!$activity) {
    echo "<div class='container'><p>Activity not found.</p></div>";
    include 'includes/footer.php';
    exit;
}
?>

<main class="container">
    <div class="activity-detail-card">
        <div class="detail-visual">
            <img src="assets/images/<?php echo $activity['image_path']; ?>" alt="<?php echo htmlspecialchars($activity['title']); ?>">
            <span class="category-badge-large"><?php echo $activity['category']; ?></span>
        </div>

        <div class="detail-body">
            <div class="detail-header-info">
                <h1><?php echo htmlspecialchars($activity['title']); ?></h1>
                <p class="detail-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($activity['location']); ?></p>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <i class="far fa-calendar-alt"></i>
                    <div>
                        <label>Date</label>
                        <p><?php echo date('F j, Y', strtotime($activity['activity_date'])); ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="far fa-clock"></i>
                    <div>
                        <label>Time</label>
                        <p><?php echo date('g:i a', strtotime($activity['activity_date'])); ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-users"></i>
                    <div>
                        <label>Availability</label>
                        <p><?php echo $activity['current_slots']; ?> / <?php echo $activity['capacity']; ?> spots left</p>
                    </div>
                </div>
            </div>

            <div class="detail-description">
                <h3>About this activity</h3>
                <p><?php echo nl2br(htmlspecialchars($activity['description'])); ?></p>
            </div>

            <div class="booking-action-area">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="login-prompt">
                        <p>Ready to join? <a href="login.php">Log in</a> to book your spot.</p>
                    </div>
                <?php elseif ($is_booked): ?>
                    <div class="confirmed-booking">
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            <span>You're going!</span>
                        </div>
                        <a href="api/cancel_booking.php?id=<?php echo $user_booking_id; ?>" 
                           class="btn-cancel-pill" 
                           onclick="return confirm('Do you want to cancel your spot?')">
                           Cancel Booking
                        </a>
                    </div>
                <?php elseif ($activity['current_slots'] <= 0): ?>
                    <button class="btn-disabled" disabled>Fully Booked</button>
                <?php else: ?>
                    <form action="api/book_activity.php" method="POST">
                        <input type="hidden" name="activity_id" value="<?php echo $activity['activity_id']; ?>">
                        <button type="submit" class="btn-book-primary">Book My Spot Now</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>