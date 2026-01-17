<?php
require_once 'includes/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic Validation
    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $hashed_password);

        if ($stmt->execute()) {
            $message = "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            if ($conn->errno == 1062) { // Duplicate entry error code
                $message = "This email is already registered.";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
        $stmt->close();
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="container">
    <h2>Register for Sydney Social</h2>
    <?php if ($message) echo "<p class='alert'>$message</p>"; ?>

    <form action="register.php" method="POST" class="auth-form">
        <label>Full Name</label>
        <input type="text" name="full_name" required placeholder="e.g. Alex Student">

        <label>Email Address</label>
        <input type="email" name="email" required placeholder="alex@student.com">

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" class="btn">Create Account</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</main>

<?php include 'includes/footer.php'; ?>