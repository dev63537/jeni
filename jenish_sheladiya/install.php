<?php
// Shyam Enterprise - One-Click Installation Script
// Run this file to set up the entire database and system

echo "<!DOCTYPE html>
<html>
<head>
    <title>Shyam Enterprise - Installation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: #0c5460; background: #d1ecf1; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .btn { background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
        h1 { color: #333; text-align: center; }
        h2 { color: #666; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .step { background: #f8f9fa; padding: 15px; margin: 10px 0; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🚀 Shyam Enterprise Installation</h1>
        <p style='text-align: center; color: #666;'>Complete setup for your printing business website</p>
";

require 'config.php';

$errors = [];
$success = [];

echo "<h2>📊 Database Setup</h2>";

try {
    // Step 1: Create users table
    $create_users_table = "
    CREATE TABLE IF NOT EXISTS `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `username` varchar(50) NOT NULL,
      `email` varchar(100) NOT NULL,
      `password` varchar(255) NOT NULL,
      `role` enum('user','manager','admin') DEFAULT 'user',
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `username` (`username`),
      UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    if ($conn->query($create_users_table) === TRUE) {
        echo "<div class='success'>✅ Users table created successfully!</div>";
    } else {
        echo "<div class='error'>❌ Error creating users table: " . $conn->error . "</div>";
        $errors[] = "Users table creation failed";
    }

    // Step 2: Create orders table
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
      `reference_image` varchar(255) DEFAULT NULL,
      `urgent_order` tinyint(1) DEFAULT 0,
      `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `status` enum('pending','in_progress','completed','cancelled') DEFAULT 'pending',
      `payment_status` enum('unpaid','paid','failed') DEFAULT 'unpaid',
      `amount` decimal(10,2) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    if ($conn->query($create_orders_table) === TRUE) {
        echo "<div class='success'>✅ Orders table created successfully!</div>";
    } else {
        echo "<div class='error'>❌ Error creating orders table: " . $conn->error . "</div>";
        $errors[] = "Orders table creation failed";
    }

    // Step 3: Create contact_messages table
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

    if ($conn->query($create_contact_table) === TRUE) {
        echo "<div class='success'>✅ Contact messages table created successfully!</div>";
    } else {
        echo "<div class='error'>❌ Error creating contact messages table: " . $conn->error . "</div>";
        $errors[] = "Contact messages table creation failed";
    }

    // Step 4: Create payments table
    $create_payments_table = "
    CREATE TABLE IF NOT EXISTS `payments` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `order_id` int(11) NOT NULL,
      `amount` decimal(10,2) NOT NULL,
      `method` varchar(50) DEFAULT 'card',
      `status` enum('success','failed') NOT NULL,
      `transaction_id` varchar(64) NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `order_idx` (`order_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    if ($conn->query($create_payments_table) === TRUE) {
        echo "<div class='success'>✅ Payments table created successfully!</div>";
    } else {
        echo "<div class='error'>❌ Error creating payments table: " . $conn->error . "</div>";
        $errors[] = "Payments table creation failed";
    }

    // Step 5: Create gallery table
    $create_gallery_table = "
    CREATE TABLE IF NOT EXISTS `gallery` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(200) NOT NULL,
      `description` text,
      `image_path` varchar(255) NOT NULL,
      `category` varchar(50) NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `is_featured` tinyint(1) DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    if ($conn->query($create_gallery_table) === TRUE) {
        echo "<div class='success'>✅ Gallery table created successfully!</div>";
    } else {
        echo "<div class='error'>❌ Error creating gallery table: " . $conn->error . "</div>";
        $errors[] = "Gallery table creation failed";
    }

    // Step 6: Create service_pricing table
    $create_pricing_table = "
    CREATE TABLE IF NOT EXISTS `service_pricing` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `service_code` varchar(50) NOT NULL,
      `service_name` varchar(200) NOT NULL,
      `base_price` decimal(10,2) DEFAULT NULL,
      `price_unit` varchar(50) DEFAULT 'per piece',
      `description` text,
      `is_active` tinyint(1) DEFAULT 1,
      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `service_code` (`service_code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    if ($conn->query($create_pricing_table) === TRUE) {
        echo "<div class='success'>✅ Service pricing table created successfully!</div>";
    } else {
        echo "<div class='error'>❌ Error creating service pricing table: " . $conn->error . "</div>";
        $errors[] = "Service pricing table creation failed";
    }

    // Step 7: Check if admin user exists and create if not
    $check_admin = $conn->query("SELECT id FROM users WHERE username = 'admin'");
    
    if ($check_admin->num_rows == 0) {
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $create_admin = "INSERT INTO users (username, email, password, role) VALUES ('admin', 'admin@shyamenterprise.com', '$admin_password', 'admin')";
        
        if ($conn->query($create_admin) === TRUE) {
            echo "<div class='success'>✅ Admin user created successfully!</div>";
            $success[] = "Admin user created";
        } else {
            echo "<div class='error'>❌ Error creating admin user: " . $conn->error . "</div>";
            $errors[] = "Admin user creation failed";
        }
    } else {
        echo "<div class='info'>ℹ️ Admin user already exists!</div>";
    }

    // Step 8: Insert sample data
    $sample_gallery = "INSERT IGNORE INTO `gallery` (`title`, `description`, `image_path`, `category`, `is_featured`) VALUES
    ('Flex Board Banner Sample', 'High-quality outdoor advertising banner', '/images/gallery/flexboard1.jpg', 'flexboard', 1),
    ('Vinyl Sticker Design', 'Custom vinyl stickers for branding', '/images/gallery/vinyl1.jpg', 'vinyl', 1),
    ('LED Board Display', 'Modern LED signage solution', '/images/gallery/led1.jpg', 'ledboard', 1),
    ('Business Card Design', 'Professional visiting card design', '/images/gallery/card1.jpg', 'visiting_card', 0)";

    if ($conn->query($sample_gallery) === TRUE) {
        echo "<div class='success'>✅ Sample gallery data inserted!</div>";
    }

    $sample_pricing = "INSERT IGNORE INTO `service_pricing` (`service_code`, `service_name`, `base_price`, `price_unit`, `description`) VALUES
    ('flexboard', 'Flex Board Banner', 25.00, 'per sq ft', 'High-quality outdoor advertising banners'),
    ('vinyl', 'Vinyl Sticker Printing', 15.00, 'per sq ft', 'Durable vinyl stickers for branding'),
    ('oneway', 'One-Way Vision Print', 45.00, 'per sq ft', 'Window graphics with one-way visibility'),
    ('reflective', 'Reflective Vinyl Print', 60.00, 'per sq ft', 'High-visibility reflective materials'),
    ('ecohd', 'Eco HD Print', 35.00, 'per sq ft', 'Eco-friendly high-definition printing'),
    ('lighting', 'Lighting Board', 150.00, 'per sq ft', 'Illuminated signage solutions'),
    ('rollup', 'Roll-Up Standee', 800.00, 'per piece', 'Portable display stands'),
    ('canopy', 'Canopy', 2500.00, 'per piece', 'Branded outdoor canopies'),
    ('ledboard', 'L.E.D Board', 200.00, 'per sq ft', 'Digital LED display boards'),
    ('safety', 'Industrial Safety Sign Board', 40.00, 'per piece', 'Safety compliance signage'),
    ('acp', 'A.C.P Board', 80.00, 'per sq ft', 'Aluminum composite panel signage'),
    ('foam', 'Foam Board', 20.00, 'per sq ft', 'Lightweight foam board displays'),
    ('visiting_card', 'Visiting Cards', 2.00, 'per piece', 'Professional business cards'),
    ('letterhead', 'Letter Head', 5.00, 'per piece', 'Branded letterhead printing'),
    ('billbook', 'Bill Book', 50.00, 'per book', 'Custom bill books'),
    ('envelope', 'Envelope', 3.00, 'per piece', 'Branded envelope printing'),
    ('brochure', 'Brochure', 8.00, 'per piece', 'Marketing brochures'),
    ('pamphlet', 'Pamphlet', 1.50, 'per piece', 'Promotional pamphlets'),
    ('idcard', 'ID Card', 15.00, 'per piece', 'Employee/Student ID cards'),
    ('stickers', 'Stickers', 10.00, 'per sheet', 'Custom sticker printing'),
    ('invitation', 'Invitation Card', 12.00, 'per piece', 'Event invitation cards')";

    if ($conn->query($sample_pricing) === TRUE) {
        echo "<div class='success'>✅ Sample pricing data inserted!</div>";
    }

    // Create uploads directory
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
        echo "<div class='success'>✅ Uploads directory created!</div>";
    }

    // Show all tables
    echo "<h2>📋 Database Tables Created</h2>";
    $tables = $conn->query("SHOW TABLES");
    if ($tables->num_rows > 0) {
        echo "<ul>";
        while($table = $tables->fetch_array()) {
            echo "<li><strong>" . $table[0] . "</strong></li>";
        }
        echo "</ul>";
    }

} catch (Exception $e) {
    echo "<div class='error'>❌ Database Error: " . $e->getMessage() . "</div>";
    $errors[] = "Database connection failed";
}

echo "<h2>🎉 Installation Complete!</h2>";

if (empty($errors)) {
    echo "<div class='success'>
        <h3>✅ Installation Successful!</h3>
        <p>Your Shyam Enterprise website is now ready to use.</p>
    </div>";
} else {
    echo "<div class='error'>
        <h3>⚠️ Installation Completed with Errors</h3>
        <p>Some components may not work properly. Please check the errors above.</p>
    </div>";
}

echo "<div class='step'>
    <h3>🔑 Admin Login Details</h3>
    <p><strong>Username:</strong> admin</p>
    <p><strong>Password:</strong> admin123</p>
    <p><em>⚠️ Please change the default password after first login for security!</em></p>
</div>";

echo "<div class='step'>
    <h3>🚀 Next Steps</h3>
    <ol>
        <li>Login to admin panel and change default password</li>
        <li>Upload your company logo and images</li>
        <li>Customize service pricing and descriptions</li>
        <li>Test all functionality</li>
        <li>Configure payment gateway (if needed)</li>
    </ol>
</div>";

echo "<div style='text-align: center; margin-top: 30px;'>
    <a href='login.php' class='btn'>🔐 Go to Login</a>
    <a href='index.php' class='btn'>🏠 Go to Homepage</a>
    <a href='admin_panel.php' class='btn'>⚙️ Admin Panel</a>
</div>";

echo "<div class='info' style='margin-top: 30px;'>
    <h3>📞 Support</h3>
    <p>If you encounter any issues, please check the README.md file for troubleshooting tips.</p>
</div>";

$conn->close();

echo "</div></body></html>";
?>
