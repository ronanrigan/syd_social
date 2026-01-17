<?php
require_once 'includes/db.php';
include 'includes/header.php';

// 1. Get the ID from the URL and sanitize it
$activity_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

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
    <div class="activity-detail">
        <div class="detail-header">
            <h1><?php echo htmlspecialchars($activity['title']); ?></h1>
            <span class="category-tag-large"><?php echo $activity['category']; ?></span>
        </div>

        <div class="detail-content">
            <div class="detail-image">
                <img src="assets/images/activities/<?php echo $activity['image_path']; ?>" alt="<?php echo $activity['title']; ?>">
            </div>

            <div class="detail-info">
                <h3>Details</h3>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($activity['location']); ?></p>
                <p><strong>Date & Time:</strong> <?php echo date('F j, Y, g:i a', strtotime($activity['activity_date'])); ?></p>
                <p><strong>Spots Remaining:</strong> <?php echo $activity['current_slots']; ?> / <?php echo $activity['capacity']; ?></p>
                
                <hr>
                
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($activity['description'])); ?></p>

                <div class="booking-section">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <p class="alert-info">Please <a href="login.php">login</a> to book this activity.</p>
                    <?php elseif ($activity['current_slots'] <= 0): ?>
                        <p class="alert-danger">This activity is fully booked!</p>
                    <?php else: ?>
                        <form action="api/book_activity.php" method="POST">
                            <input type="hidden" name="activity_id" value="<?php echo $activity['activity_id']; ?>">
                            <button type="submit" class="btn-book">Book My Spot Now</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>