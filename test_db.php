<?php
// Include the database connection file we just created
require_once 'includes/db.php';

echo "<h2>Database Connection Test</h2>";

// 1. Check if the connection variable exists
if (isset($conn)) {
    echo "<p style='color: green;'>✔ Connection variable is successfully initialized.</p>";
} else {
    echo "<p style='color: red;'>✘ Connection variable is missing. Check your includes/db.php.</p>";
    exit;
}

// 2. Check for connection errors
if ($conn->connect_error) {
    echo "<p style='color: red;'>✘ Connection failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✔ Successfully connected to the <strong>" . DB_NAME . "</strong> database.</p>";
}

echo "<hr>";

// 3. Test a simple query to fetch activities
echo "<h3>Activity Data Test</h3>";
$sql = "SELECT title, location, category FROM activities";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<p>Found <strong>" . $result->num_rows . "</strong> activities in the database:</p>";
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['title']) . " - <em>" . $row['category'] . "</em> (" . $row['location'] . ")</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: orange;'>! The connection works, but the 'activities' table is empty. Have you run the sample_data.sql yet?</p>";
}

// 4. Close connection (optional for this test)
$conn->close();
?>