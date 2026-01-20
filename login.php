<?php
// session_start must be at the very top of the file
session_start(); 
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
            // Updated to generic error for security
            $message = "Invalid email or password."; 
        }
    } else {
        $message = "Invalid email or password.";
    }
    $stmt->close();
}
?>

<?php include 'includes/header.php'; ?>

<main class="auth-page-wrapper">
    <div class="auth-card">
        <h2>Welcome Back</h2>
        <p class="subtitle">Log in to manage your Sydney social calendar.</p>

        <?php if ($message): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="auth-group">
                <label>Email Address</label>
                <input type="email" name="email" class="auth-input" placeholder="alex@student.com" required>
            </div>
            
            <div class="auth-group">
                <label>Password</label>
                <input type="password" name="password" class="auth-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-primary btn-auth">Login to Account</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>