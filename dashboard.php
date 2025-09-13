<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details from database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Redirect admin users to admin panel
if ($user && $user['role'] === 'admin') {
    header("Location: admin_panel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/base.css" />
    <link rel="stylesheet" href="css/dashboard.css" />
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
                <li class="nav-item"><a href="dashboard.php" class="nav-link active">Dashboard</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="welcome-card">
            <h1 class="welcome-title">Welcome Back!</h1>
            <p class="welcome-subtitle">Hello, <?php echo htmlspecialchars($user['username']); ?>! Here's your dashboard.</p>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-icon">👤</div>
                <h3 class="card-title">Profile</h3>
                <p class="card-description">View and edit your account information</p>
                <a href="#" class="card-button">View Profile</a>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">⚙️</div>
                <h3 class="card-title">Settings</h3>
                <p class="card-description">Manage your account settings and preferences</p>
                <a href="#" class="card-button">Settings</a>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">📊</div>
                <h3 class="card-title">Analytics</h3>
                <p class="card-description">View your account statistics and activity</p>
                <a href="#" class="card-button">View Analytics</a>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">🚪</div>
                <h3 class="card-title">Logout</h3>
                <p class="card-description">Safely sign out of your account</p>
                <a href="logout.php" class="card-button">Logout</a>
            </div>
        </div>
    </div>

</body>

</html>
