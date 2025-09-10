<?php
// Database setup script for Shyam Enterprise
require 'config.php';

echo "<h2>Database Setup for Shyam Enterprise</h2>";

// Create users table
$create_users_table = "
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// Create orders table
$create_orders_table = "
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

// Create contact_messages table
$create_contact_table = "
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

try {
    // Create users table
    if ($conn->query($create_users_table) === TRUE) {
        echo "<p style='color: green;'>✅ Users table created successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating users table: " . $conn->error . "</p>";
    }

    // Create orders table
    if ($conn->query($create_orders_table) === TRUE) {
        echo "<p style='color: green;'>✅ Orders table created successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating orders table: " . $conn->error . "</p>";
    }

    // Create contact_messages table
    if ($conn->query($create_contact_table) === TRUE) {
        echo "<p style='color: green;'>✅ Contact messages table created successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating contact messages table: " . $conn->error . "</p>";
    }

    // Check if admin user exists
    $check_admin = $conn->query("SELECT id FROM users WHERE username = 'admin'");
    
    if ($check_admin->num_rows == 0) {
        // Create admin user
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $create_admin = "INSERT INTO users (username, email, password, role) VALUES ('admin', 'admin@shyamenterprise.com', '$admin_password', 'admin')";
        
        if ($conn->query($create_admin) === TRUE) {
            echo "<p style='color: green;'>✅ Admin user created successfully!</p>";
            echo "<p><strong>Admin Login Details:</strong></p>";
            echo "<p>Username: <strong>admin</strong></p>";
            echo "<p>Password: <strong>admin123</strong></p>";
        } else {
            echo "<p style='color: red;'>❌ Error creating admin user: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: blue;'>ℹ️ Admin user already exists!</p>";
    }

    // Show all tables
    echo "<h3>Current Database Tables:</h3>";
    $tables = $conn->query("SHOW TABLES");
    if ($tables->num_rows > 0) {
        echo "<ul>";
        while($table = $tables->fetch_array()) {
            echo "<li>" . $table[0] . "</li>";
        }
        echo "</ul>";
    }

    echo "<p><a href='login.php' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login Page</a></p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?>
