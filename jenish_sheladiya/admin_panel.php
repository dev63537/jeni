<?php
session_start();
require 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details and check if admin
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || $user['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Get statistics
$stats = [];

// Total users
$result = $conn->query("SELECT COUNT(*) as count FROM users");
$stats['users'] = $result->fetch_assoc()['count'];

// Total orders
$result = $conn->query("SELECT COUNT(*) as count FROM orders");
$stats['orders'] = $result->fetch_assoc()['count'];

// Pending orders
$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
$stats['pending_orders'] = $result->fetch_assoc()['count'];

// Total contact messages
$result = $conn->query("SELECT COUNT(*) as count FROM contact_messages");
$stats['messages'] = $result->fetch_assoc()['count'];

// Recent orders
$recent_orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC LIMIT 10");

// Recent contact messages
$recent_messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin Panel - Shyam Enterprise</title>
    <link rel="stylesheet" href="css/base.css" />
    <link rel="stylesheet" href="css/dashboard.css" />
    <style>
        .data-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .data-table th, .data-table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        .data-table th { background: #f8f9fa; font-weight: 600; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 500; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-new { background: #cce5ff; color: #004085; }
    </style>
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
                <li class="nav-item"><a href="admin_panel.php" class="nav-link active">Admin Panel</a></li>
                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <header class="page-hero">
        <div class="container">
            <h1>Admin Panel</h1>
            <p>Welcome, <?php echo htmlspecialchars($user['username']); ?>! Manage your website data.</p>
        </div>
    </header>

    <div class="dashboard-container">
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['users']; ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['orders']; ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending_orders']; ?></div>
                <div class="stat-label">Pending Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['messages']; ?></div>
                <div class="stat-label">Contact Messages</div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="data-section">
            <h3>📋 Recent Orders</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $recent_orders->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['service_type']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($order['order_date'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Recent Contact Messages -->
        <div class="data-section">
            <h3>📧 Recent Contact Messages</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($message = $recent_messages->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $message['id']; ?></td>
                        <td><?php echo htmlspecialchars($message['name']); ?></td>
                        <td><?php echo htmlspecialchars($message['email']); ?></td>
                        <td><?php echo htmlspecialchars(substr($message['subject'], 0, 30)) . '...'; ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $message['status']; ?>">
                                <?php echo ucfirst($message['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($message['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-icon">👥</div>
                <h3 class="card-title">Manage Users</h3>
                <p class="card-description">View and manage user accounts</p>
                <a href="admin_users.php" class="card-button">Manage Users</a>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">📋</div>
                <h3 class="card-title">Manage Orders</h3>
                <p class="card-description">View and update order status</p>
                <a href="admin_orders.php" class="card-button">Manage Orders</a>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">📧</div>
                <h3 class="card-title">Contact Messages</h3>
                <p class="card-description">View and respond to messages</p>
                <a href="admin_messages.php" class="card-button">View Messages</a>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">⚙️</div>
                <h3 class="card-title">Settings</h3>
                <p class="card-description">Website configuration</p>
                <a href="#" class="card-button">Settings</a>
            </div>
        </div>
    </div>

</body>

</html>
