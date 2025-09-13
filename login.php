<?php
session_start();
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_or_email = trim($_POST['username_or_email']);
    $password = $_POST['password'];

    // Fetch a single user match deterministically
    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE username = ? OR email = ? LIMIT 1");
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $email, $stored_password);
        $stmt->fetch();

        $is_valid = password_verify($password, $stored_password);

        // Backward-compat: if passwords were stored in plain text previously
        if (!$is_valid && hash_equals($stored_password, $password)) {
            $new_hash = password_hash($password, PASSWORD_DEFAULT);
            $rehash = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $rehash->bind_param("si", $new_hash, $id);
            $rehash->execute();
            $rehash->close();
            $stored_password = $new_hash;
            $is_valid = true;
        }

        if ($is_valid) {
            // Rehash if needed
            if (password_needs_rehash($stored_password, PASSWORD_DEFAULT)) {
                $new_hash2 = password_hash($password, PASSWORD_DEFAULT);
                $upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $upd->bind_param("si", $new_hash2, $id);
                $upd->execute();
                $upd->close();
            }

            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Invalid credentials.";
        }
    } else {
        $message = "Invalid credentials.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="css/base.css" />
    <link rel="stylesheet" href="css/form.css" />
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">Shyam Enterprise</a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
                <li class="nav-item"><a href="order.php" class="nav-link">Order Now</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About Us</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a href="login.php" class="nav-link active">Login</a></li>
                    <li class="nav-item"><a href="signup.php" class="nav-link">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Centered container for the login form -->
    <div class="centered-container">
        <form action="login.php" method="POST" class="login-form">
            <h2>Shyam Enterprise</h2>
            <?php if ($message) echo '<div class="message">' . htmlspecialchars($message) . '</div>'; ?>
            <input type="text" name="username_or_email" placeholder="Username or email" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Log In</button>
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </form>
    </div>

</body>

</html>