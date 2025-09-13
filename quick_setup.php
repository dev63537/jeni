<?php
// Quick database setup - run this first!
require 'config.php';

echo "<h2>Quick Database Setup</h2>";
echo "<p>Setting up database tables...</p>";

try {
    // Create users table (without role column first)
    $create_users = "
    CREATE TABLE IF NOT EXISTS `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `username` varchar(50) NOT NULL,
      `email` varchar(100) NOT NULL,
      `password` varchar(255) NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `username` (`username`),
      UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    if ($conn->query($create_users) === TRUE) {
        echo "<p style='color: green;'>✅ Users table created!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
    }

    // Create orders table
    $create_orders = "
    CREATE TABLE IF NOT EXISTS `orders` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `customer_name` varchar(100) NOT NULL,
      `email` varchar(100) NOT NULL,
      `phone` varchar(20) NOT NULL,
      `service_type` varchar(50) NOT NULL,
      `quantity` int(11) DEFAULT 1,
      `size` varchar(100) DEFAULT NULL,
      `description` text DEFAULT NULL,
      `urgent_order` tinyint(1) DEFAULT 0,
      `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `status` enum('pending','in_progress','completed','cancelled') DEFAULT 'pending',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    if ($conn->query($create_orders) === TRUE) {
        echo "<p style='color: green;'>✅ Orders table created!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
    }

    // Create contact_messages table
    $create_contact = "
    CREATE TABLE IF NOT EXISTS `contact_messages` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(100) NOT NULL,
      `email` varchar(100) NOT NULL,
      `phone` varchar(20) NOT NULL,
      `subject` varchar(200) NOT NULL,
      `message` text NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `status` enum('new','read','replied') DEFAULT 'new',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    if ($conn->query($create_contact) === TRUE) {
        echo "<p style='color: green;'>✅ Contact messages table created!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
    }

    // Check if admin user exists
    $check_admin = $conn->query("SELECT id FROM users WHERE username = 'admin'");
    
    if ($check_admin->num_rows == 0) {
        // Create admin user
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $create_admin = "INSERT INTO users (username, email, password) VALUES ('admin', 'admin@shyamenterprise.com', '$admin_password')";
        
        if ($conn->query($create_admin) === TRUE) {
            echo "<p style='color: green;'>✅ Admin user created!</p>";
            echo "<p><strong>Admin Login:</strong></p>";
            echo "<p>Username: <strong>admin</strong></p>";
            echo "<p>Password: <strong>admin123</strong></p>";
        } else {
            echo "<p style='color: red;'>❌ Error creating admin: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: blue;'>ℹ️ Admin user already exists!</p>";
    }

    echo "<hr>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<p>1. <a href='add_admin_role.php' style='color: blue;'>Add Admin Role Support</a></p>";
    echo "<p>2. <a href='login.php' style='color: blue;'>Login as Admin</a></p>";
    echo "<p>3. <a href='index.php' style='color: blue;'>Go to Homepage</a></p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?>
