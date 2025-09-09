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
$stmt = $conn->prepare("SELECT id, username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle logout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_logout'])) {
    // Destroy session
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Logout - Your Account</title>
    <link rel="stylesheet" href="css/base.css" />
    <link rel="stylesheet" href="css/logout.css" />
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">YourSite</a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
                <li class="nav-item"><a href="order.php" class="nav-link">Order Now</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About Us</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="logout-container">
        <div class="logout-card">
            <div class="user-icon">👤</div>
            <h1 class="logout-title">Account Logout</h1>
            <p class="logout-subtitle">Review your account details before signing out</p>
            
            <div class="user-details">
                <div class="detail-row">
                    <span class="detail-label">User ID</span>
                    <span class="detail-value">#<?php echo htmlspecialchars($user['id']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Username</span>
                    <span class="detail-value"><?php echo htmlspecialchars($user['username']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value"><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Login Status</span>
                    <span class="detail-value" style="color: #27ae60;">● Active</span>
                </div>
            </div>

            <form method="POST" style="display: inline;">
                <div class="logout-buttons">
                    <button type="submit" name="confirm_logout" class="btn btn-danger">
                        🚪 Confirm Logout
                    </button>
                    <a href="dashboard.php" class="btn btn-secondary">
                        ← Back to Dashboard
                    </a>
                </div>
            </form>

            <p class="warning-text">
                ⚠️ Once you logout, you'll need to login again to access your account
            </p>
        </div>
    </div>

</body>

</html>
