<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<section class="hero">
    <div class="hero-content">
        <h1>Discover Sydney Together</h1>
        <p>Find wellness, social, and cultural activities near you.</p>
        <a href="activities.php" class="btn-primary">Browse All Activities</a>
    </div>
</section>

<main class="container">
    <div class="section-header">
        <h2>Featured Activities</h2>
        <p>Hand-picked experiences for your lifestyle.</p>
    </div>

    <div class="activity-grid">
        <?php
        $sql = "SELECT * FROM activities LIMIT 3"; // Show the top 3 on home page
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                <div class="activity-card">
                    <div class="card-image">
                        <img src="assets/images/<?php echo $row['image_path']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <span class="category-tag"><?php echo $row['category']; ?></span>
                    </div>
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['location']); ?></p>
                        <p class="date"><?php echo date('M d, Y', strtotime($row['activity_date'])); ?></p>
                        <div class="card-footer">
                            <span class="slots"><?php echo $row['current_slots']; ?> spots left</span>
                            <a href="activity.php?id=<?php echo $row['activity_id']; ?>" class="btn-outline">View Details</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No activities found.</p>";
        }
        ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>