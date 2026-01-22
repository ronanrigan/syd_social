<?php
require_once 'includes/db.php';
include 'includes/header.php';

$activity_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$is_booked = false; 
$user_booking_id = 0;

// 1. Check if user is logged in AND if they have a booking for this activity
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $check_stmt = $conn->prepare("SELECT booking_id FROM bookings WHERE user_id = ? AND activity_id = ?");
    $check_stmt->bind_param("ii", $user_id, $activity_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $is_booked = true; 
        $booking_data = $check_result->fetch_assoc();
        $user_booking_id = $booking_data['booking_id'];
    }
    $check_stmt->close();
}

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

// 2. Logic: Check if the event has passed
$current_time = new DateTime();
$activity_time = new DateTime($activity['activity_date']);
$passed_deadline = $current_time > $activity_time;

// 3. Logic: Check if the user is authorized to review (must be registered AND event must be passed)
$can_review = $is_booked && $passed_deadline;
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
                <?php if ($passed_deadline): ?>
                    <div class="event-passed-notice" style="background: #f1f5f9; padding: 15px; border-radius: 10px; color: #64748b;">
                        <p><i class="fas fa-history"></i> This event has concluded.</p>
                    </div>
                <?php elseif (!isset($_SESSION['user_id'])): ?>
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

    <div class="reviews-wrapper" style="margin-top: 40px;">
        <h2 style="margin-bottom: 20px;">Community Feedback</h2>

        <?php if ($can_review): ?>
            <div class="auth-card" style="max-width: 100%; text-align: left; margin-bottom: 30px;">
                <h3>Share Your Experience</h3>
                <p class="subtitle" style="font-size: 0.9rem; color: #64748b; margin-bottom: 15px;">Since you participated in this event, we'd love to hear your thoughts!</p>
                <form action="api/submit_reviews.php" method="POST">
                    <input type="hidden" name="activity_id" value="<?php echo $activity_id; ?>">
                    <div class="auth-group">
                        <label>Rating</label>
                        <select name="rating" class="auth-input" required>
                            <option value="5">★★★★★ - Excellent</option>
                            <option value="4">★★★★☆ - Good</option>
                            <option value="3">★★★☆☆ - Average</option>
                            <option value="2">★★☆☆☆ - Poor</option>
                            <option value="1">★☆☆☆☆ - Terrible</option>
                        </select>
                    </div>
                    <div class="auth-group" style="margin-top: 15px;">
                        <label>Your Feedback</label>
                        <textarea name="comment" class="auth-input" rows="4" placeholder="How was the event?" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary" style="margin-top: 15px;">Post Review</button>
                </form>
            </div>
        <?php elseif ($passed_deadline): ?>
            <p style="background: #f8fafc; padding: 20px; border-radius: 12px; color: #64748b; border: 1px solid #e2e8f0;">
                <i class="fas fa-info-circle"></i> Reviews are only open to participants who booked this activity.
            </p>
        <?php endif; ?>

        <div class="reviews-list">
            <?php
            $rev_stmt = $conn->prepare("SELECT r.*, u.full_name FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE r.activity_id = ? ORDER BY r.created_at DESC");
            $rev_stmt->bind_param("i", $activity_id);
            $rev_stmt->execute();
            $reviews = $rev_stmt->get_result();

            if ($reviews->num_rows > 0):
                while($rev = $reviews->fetch_assoc()): ?>
                    <div class="review-item" style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong><?php echo htmlspecialchars($rev['full_name']); ?></strong>
                            <span style="color: #fbbf24;"><?php echo str_repeat('★', $rev['rating']); ?></span>
                        </div>
                        
                        <div id="review-display-<?php echo $rev['review_id']; ?>">
                            <p style="color: #64748b; margin-top: 10px;"><?php echo htmlspecialchars($rev['comment']); ?></p>
                            <div style="margin-top: 10px; display: flex; gap: 15px; align-items: center;">
                                <small style="color: #cbd5e1;"><?php echo date('M j, Y', strtotime($rev['created_at'])); ?></small>
                                
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $rev['user_id']): ?>
                                    <button onclick="toggleEdit(<?php echo $rev['review_id']; ?>)" style="background:none; border:none; color:var(--primary); cursor:pointer; font-size: 0.8rem; padding:0;">Edit</button>
                                    <a href="api/delete_review.php?id=<?php echo $rev['review_id']; ?>&activity_id=<?php echo $activity_id; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this review?')" 
                                       style="color: #e11d48; text-decoration:none; font-size: 0.8rem;">Delete</a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $rev['user_id']): ?>
                        <form id="edit-form-<?php echo $rev['review_id']; ?>" action="api/edit_review.php" method="POST" style="display: none; margin-top: 15px;">
                            <input type="hidden" name="review_id" value="<?php echo $rev['review_id']; ?>">
                            <input type="hidden" name="activity_id" value="<?php echo $activity_id; ?>">
                            <div class="auth-group">
                                <textarea name="comment" class="auth-input" rows="3" required><?php echo htmlspecialchars($rev['comment']); ?></textarea>
                            </div>
                            <div style="margin-top: 10px; display: flex; gap: 10px;">
                                <button type="submit" class="btn-primary" style="padding: 5px 15px; font-size: 0.8rem;">Update</button>
                                <button type="button" onclick="toggleEdit(<?php echo $rev['review_id']; ?>)" class="btn-cancel-pill" style="padding: 5px 15px; font-size: 0.8rem;">Cancel</button>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile;
            else: ?>
                <p class="text-light">No reviews yet for this activity.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
function toggleEdit(reviewId) {
    const displayDiv = document.getElementById('review-display-' + reviewId);
    const formDiv = document.getElementById('edit-form-' + reviewId);
    
    if (formDiv.style.display === 'none') {
        displayDiv.style.display = 'none';
        formDiv.style.display = 'block';
    } else {
        displayDiv.style.display = 'block';
        formDiv.style.display = 'none';
    }
}
</script>

<?php include 'includes/footer.php'; ?>