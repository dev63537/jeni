<?php
// One-time migration to add payment columns and table
require 'config.php';

echo "<h2>Payments Migration</h2>";

$messages = [];

// Add columns to orders if missing
function ensureColumn($conn, $table, $column, $definition) {
    $res = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    if ($res && $res->num_rows === 0) {
        $sql = "ALTER TABLE `$table` ADD COLUMN $definition";
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green'>Added $table.$column</p>";
        } else {
            echo "<p style='color:red'>Failed adding $table.$column: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:blue'>$table.$column already exists</p>";
    }
}

// Ensure orders table exists
$conn->query("CREATE TABLE IF NOT EXISTS `orders` (id INT AUTO_INCREMENT PRIMARY KEY) ENGINE=InnoDB");

ensureColumn($conn, 'orders', 'payment_status', "`payment_status` enum('unpaid','paid','failed') DEFAULT 'unpaid'");
ensureColumn($conn, 'orders', 'amount', "`amount` decimal(10,2) DEFAULT NULL");

// Create payments table if not exists
$create_payments = "CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(50) DEFAULT 'card',
  `status` enum('success','failed') NOT NULL,
  `transaction_id` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_idx` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($create_payments) === TRUE) {
    echo "<p style='color:green'>Payments table ready.</p>";
} else {
    echo "<p style='color:red'>Error creating payments table: " . $conn->error . "</p>";
}

echo "<p><a href='order.php' style='background:#667eea;color:#fff;padding:10px 14px;border-radius:6px;text-decoration:none'>Go to Order Page</a></p>";

$conn->close();
?>
