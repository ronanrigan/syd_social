<?php
require_once 'includes/db.php';

$message = "";
$message_type = ""; // To distinguish between error and success styling

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Validation Checks
    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
        $message_type = "error";
    } elseif (strlen($password) < 5) {
        $message = "Password must be at least 5 characters long.";
        $message_type = "error";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $message = "Password must contain at least one number.";
        $message_type = "error";
    } else {
        // 2. Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3. Insert into Database
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $full_name, $email, $hashed_password);

        if ($stmt->execute()) {
            $message = "Account created successfully! You can now <a href='login.php' class='alert-link'>login here</a>.";
            $message_type = "success";
        } else {
            if ($conn->errno == 1062) {
                $message = "This email is already registered.";
                $message_type = "error";
            } else {
                $message = "Error: " . $conn->error;
                $message_type = "error";
            }
        }
        $stmt->close();
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="auth-page-wrapper">
    <div class="auth-card">
        <h2>Create Account</h2>
        <p class="subtitle">Join the hub to discover activities across the city.</p>

        <?php if ($message): ?>
            <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" id="registerForm">
            <div class="auth-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="auth-input" placeholder="e.g. Alex Student" required>
            </div>

            <div class="auth-group">
                <label>Email Address</label>
                <input type="email" name="email" class="auth-input" placeholder="alex@student.com" required>
            </div>
            
            <div class="auth-group">
                <label>Password</label>
                <input type="password" name="password" id="password" class="auth-input" placeholder="Min. 5 chars + 1 number" required>
            </div>

            <div class="auth-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="auth-input" placeholder="Repeat your password" required>
                <span id="password-error" style="color: var(--danger); font-size: 0.8rem; display: none; margin-top: 5px;">
                    <i class="fas fa-exclamation-circle"></i> Passwords do not match.
                </span>
            </div>

            <button type="submit" class="btn-primary btn-auth">Create My Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>