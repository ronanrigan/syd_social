<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
if (!isAdmin()) { header("Location: ../login.php"); exit(); }
include '../includes/header.php';

$result = $conn->query("SELECT * FROM activities ORDER BY activity_date DESC");
?>

<main class="container">
    <div class="admin-header">
        <div>
            <h2>Admin Dashboard</h2>
            <p class="text-light">Manage your platform's social and wellness activities.</p>
        </div>
        <a href="activity_add.php" class="btn-primary">+ Add New Activity</a>
    </div>

    <div class="bookings-container">
        <table class="bookings-table">
            <thead>
                <tr>
                    <th>Activity Details</th>
                    <th>Category</th>
                    <th>Schedule</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <div class="booking-title"><?php echo htmlspecialchars($row['title']); ?></div>
                        <div class="booking-meta"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['location']); ?></div>
                    </td>
                    <td><span class="badge"><?php echo $row['category']; ?></span></td>
                    <td>
                        <div class="booking-time-large"><?php echo date('M d, Y', strtotime($row['activity_date'])); ?></div>
                        <div class="text-light" style="font-size: 0.8rem;"><?php echo date('g:i a', strtotime($row['activity_date'])); ?></div>
                    </td>
                    <td>
                        <div class="booking-title"><?php echo $row['current_slots']; ?> / <?php echo $row['capacity']; ?></div>
                        <div class="text-light" style="font-size: 0.8rem;">spots remaining</div>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="activity_edit.php?id=<?php echo $row['activity_id']; ?>" class="link-view">Edit</a>
                            <a href="activity_delete.php?id=<?php echo $row['activity_id']; ?>" 
                               class="btn-cancel-pill" 
                               onclick="return confirm('Permanently delete this activity?')">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer.php'; ?>