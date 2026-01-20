<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
if (!isAdmin()) { header("Location: ../login.php"); exit(); }

$message = "";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch existing data
$res = $conn->query("SELECT * FROM activities WHERE activity_id = $id");
$act = $res->fetch_assoc();

if (!$act) { header("Location: index.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $loc = $_POST['location'];
    $date = $_POST['activity_date'];
    $cap = intval($_POST['capacity']);
    $cat = $_POST['category'];
    $file_name = $act['image_path']; // Default to old image

    // Optional New Image Upload
    if (!empty($_FILES["activity_image"]["name"])) {
        $target_dir = "../assets/images/";
        $file_name = time() . "_" . basename($_FILES["activity_image"]["name"]);
        move_uploaded_file($_FILES["activity_image"]["tmp_name"], $target_dir . $file_name);
    }

    $stmt = $conn->prepare("UPDATE activities SET title=?, description=?, location=?, activity_date=?, capacity=?, category=?, image_path=? WHERE activity_id=?");
    $stmt->bind_param("ssssissi", $title, $desc, $loc, $date, $cap, $cat, $file_name, $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?updated=1");
        exit();
    } else {
        $message = "Error updating activity: " . $conn->error;
    }
}

include '../includes/header.php';
?>

<main class="container">
    <div class="admin-header">
        <div>
            <h2>Edit Activity</h2>
            <p class="text-light">Update the details for "<?php echo htmlspecialchars($act['title']); ?>"</p>
        </div>
        <a href="index.php" class="btn-outline">Cancel</a>
    </div>

    <div class="bookings-container">
        <?php if($message): ?>
            <p class="alert"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="activity_edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="modern-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Activity Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($act['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="Social" <?php if($act['category'] == 'Social') echo 'selected'; ?>>Social</option>
                        <option value="Wellness" <?php if($act['category'] == 'Wellness') echo 'selected'; ?>>Wellness</option>
                        <option value="Culture" <?php if($act['category'] == 'Culture') echo 'selected'; ?>>Culture</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5" required><?php echo htmlspecialchars($act['description']); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($act['location']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Date & Time</label>
                    <input type="datetime-local" name="activity_date" value="<?php echo date('Y-m-d\TH:i', strtotime($act['activity_date'])); ?>" required>
                </div>
                <div class="form-group">
                    <label>Total Capacity</label>
                    <input type="number" name="capacity" value="<?php echo $act['capacity']; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Update Image (Leave blank to keep current)</label>
                <div class="file-upload-wrapper" style="display: flex; align-items: center; gap: 20px; text-align: left;">
                    <img src="../assets/images/<?php echo $act['image_path']; ?>" style="width: 100px; height: 60px; object-fit: cover; border-radius: 8px;">
                    <input type="file" name="activity_image" accept="image/*">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Update Activity</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>