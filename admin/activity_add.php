<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
if (!isAdmin()) { header("Location: ../login.php"); exit(); }

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $loc = $_POST['location'];
    $date = $_POST['activity_date'];
    $cap = intval($_POST['capacity']);
    $cat = $_POST['category'];
    
    // Image Upload Logic
    $target_dir = "../assets/images/";
    $file_name = time() . "_" . basename($_FILES["activity_image"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["activity_image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO activities (title, description, location, activity_date, capacity, current_slots, category, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiiss", $title, $desc, $loc, $date, $cap, $cap, $cat, $file_name);
        
        if ($stmt->execute()) {
            header("Location: index.php?success=1");
            exit();
        } else {
            $message = "Database error: " . $conn->error;
        }
    } else {
        $message = "Failed to upload image.";
    }
}

include '../includes/header.php';
?>

<main class="container">
    <div class="admin-header">
        <div>
            <h2>Add New Activity</h2>
            <p class="text-light">Fill in the details below to publish a new event.</p>
        </div>
        <a href="index.php" class="btn-outline">Back to Dashboard</a>
    </div>

    <div class="bookings-container">
        <?php if($message): ?>
            <p class="alert"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="activity_add.php" method="POST" enctype="multipart/form-data" class="modern-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Activity Title</label>
                    <input type="text" name="title" placeholder="e.g., Bondi Sunrise Yoga" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="Social">Social</option>
                        <option value="Wellness">Wellness</option>
                        <option value="Culture">Culture</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5" placeholder="Describe the activity..." required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" placeholder="e.g., Bondi Beach" required>
                </div>
                <div class="form-group">
                    <label>Activity Date & Time</label>
                    <input type="datetime-local" name="activity_date" required>
                </div>
                <div class="form-group">
                    <label>Total Capacity</label>
                    <input type="number" name="capacity" min="1" placeholder="e.g., 20" required>
                </div>
            </div>

            <div class="form-group">
                <label>Activity Image</label>
                <div class="file-upload-wrapper">
                    <input type="file" name="activity_image" accept="image/*" required>
                    <p class="text-light" style="font-size: 0.8rem; margin-top: 5px;">Recommended size: 800x600px (JPG/PNG)</p>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Publish Activity</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>