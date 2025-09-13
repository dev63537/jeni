<?php
require 'config.php';

echo "<h2>Migrate: add 'manager' role</h2>";

try {
    // Detect current enum
    $res = $conn->query("SHOW COLUMNS FROM users LIKE 'role'");
    if ($res && $row = $res->fetch_assoc()) {
        $type = $row['Type'];
        if (strpos($type, "'manager'") === false) {
            $sql = "ALTER TABLE users MODIFY role ENUM('user','manager','admin') DEFAULT 'user'";
            if ($conn->query($sql) === TRUE) {
                echo "<p style='color:green'>Updated users.role to include 'manager'.</p>";
            } else {
                echo "<p style='color:red'>Failed: " . $conn->error . "</p>";
            }
        } else {
            echo "<p style='color:blue'>Manager role already present.</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color:red'>" . $e->getMessage() . "</p>";
}

echo "<p><a href='admin_users.php' style='background:#0e66cc;color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none'>Go to Manage Users</a></p>";

$conn->close();
?>
