<?php
require 'config.php';

echo "<h2>Migrate: add reference_image to orders</h2>";

try {
    $res = $conn->query("SHOW COLUMNS FROM orders LIKE 'reference_image'");
    if ($res && $res->num_rows === 0) {
        $sql = "ALTER TABLE orders ADD COLUMN reference_image VARCHAR(255) DEFAULT NULL AFTER description";
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green'>Added orders.reference_image</p>";
        } else {
            echo "<p style='color:red'>Failed: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:blue'>reference_image already exists</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>" . $e->getMessage() . "</p>";
}

echo "<p><a href='order.php' style='background:#667eea;color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none'>Go to Order Page</a></p>";

$conn->close();
?>
