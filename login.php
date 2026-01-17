<?php
require_once 'includes/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare statement to find the user
    $stmt = $conn->prepare("SELECT user_id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Check if the password matches the hash in the DB
        if (password_verify($password, $user['password'])) {
            // Success! Store user data in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "No account found with that email.";
    }
    $stmt->close();
}
?>

<?php include 'includes/header.php'; ?>

<main class="container">
    <h2>Login to Sydney Social</h2>
    <?php if ($message) echo "<p class='alert'>$message</p>"; ?>

    <form action="login.php" method="POST" class="auth-form">
        <label>Email Address</label>
        <input type="email" name="email" required placeholder="your@email.com">

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</main>

<?php include 'includes/footer.php'; ?>