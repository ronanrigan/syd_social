<?php
require_once '../includes/db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Build the query dynamically
$sql = "SELECT * FROM activities WHERE 1=1";
$params = [];
$types = "";

if ($search != '') {
    $sql .= " AND title LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}
if ($category != '') {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Echo the same card HTML used in index.php
        echo "<div class='activity-card'>
            <div class='card-image'>
                <img src='assets/images/{$row['image_path']}' alt='{$row['title']}'>
                <p class='category-tag'>".htmlspecialchars($row['category'])."</p>
            </div>
            <div class='card-content'>
                <h3>" . htmlspecialchars($row['title']) . "</h3>
                <a href='activity.php?id={$row['activity_id']}' class='btn-outline'>View Details</a>
            </div>
        </div>";
    }
} else {
    echo "<p>No activities match your search.</p>";
}
$stmt->close();
?>