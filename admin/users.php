<?php
require_once '../includes/db.php';
require_once '../includes/auth.php'; // Ensure this contains your isAdmin() check
if (!isAdmin()) { header("Location: ../login.php"); exit(); }
include '../includes/header.php';

$result = $conn->query("SELECT user_id, full_name, email, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC");
?>

<main class="container">
    <div class="admin-header">
        <div>
            <h2>User Management</h2>
            <p class="text-light">Review and manage registered members of the hub.</p>
        </div>
        <a href="index.php" class="btn-primary">Back to Dashboard</a>
    </div>

    <div class="bookings-container">
        <table class="bookings-table">
            <thead>
                <tr>
                    <th>User Details</th>
                    <th>Email Address</th>
                    <th>Join Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <div class="booking-title"><?php echo htmlspecialchars($row['full_name']); ?></div>
                        <div class="text-light" style="font-size: 0.8rem;">ID: #<?php echo $row['user_id']; ?></div>
                    </td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <div class="action-group">
                            <a href="../api/admin_delete_user.php?id=<?php echo $row['user_id']; ?>" 
                               class="btn-cancel-pill"
                               onclick="return confirm('Permanently delete this user? All their bookings and reviews will be removed.')">
                               Delete User
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer.php'; ?>