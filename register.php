<?php
session_start();
require 'config.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
        $message_type = 'error';
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "This user already exists! Please login instead.";
            $message_type = 'warning';
        } else {
            // Hash password and insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $message = "Registration successful! You can now login.";
                $message_type = 'success';
            } else {
                $message = "Error: " . $stmt->error;
                $message_type = 'error';
            }
        }
        $stmt->close();
    }
} else {
    header("Location: signup.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Registration Result</title>
    <link rel="stylesheet" href="css/base.css" />
    <link rel="stylesheet" href="css/form.css" />
    <link rel="stylesheet" href="css/popup.css" />
</head>

<body>
    <div class="popup-overlay">
        <div class="popup <?php echo $message_type; ?>">
            <?php if ($message_type == 'success'): ?>
                <div class="popup-icon">✅</div>
                <h2>Success!</h2>
                <p><?php echo htmlspecialchars($message); ?></p>
                <div class="popup-buttons">
                    <a href="login.php" class="btn btn-primary">Login Now</a>
                    <a href="index.php" class="btn btn-secondary">Go Home</a>
                </div>
            <?php elseif ($message_type == 'warning'): ?>
                <div class="popup-icon">⚠️</div>
                <h2>User Already Exists!</h2>
                <p><?php echo htmlspecialchars($message); ?></p>
                <div class="popup-buttons">
                    <a href="login.php" class="btn btn-warning">Login Instead</a>
                    <a href="signup.php" class="btn btn-secondary">Try Again</a>
                </div>
            <?php else: ?>
                <div class="popup-icon">❌</div>
                <h2>Error!</h2>
                <p><?php echo htmlspecialchars($message); ?></p>
                <div class="popup-buttons">
                    <a href="signup.php" class="btn btn-primary">Try Again</a>
                    <a href="index.php" class="btn btn-secondary">Go Home</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-close popup after 5 seconds (optional)
        setTimeout(function() {
            if ('<?php echo $message_type; ?>' === 'success') {
                window.location.href = 'login.php';
            }
        }, 5000);
    </script>
</body>

</html>
