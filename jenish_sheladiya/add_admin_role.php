<?php
// Add role column to users table
require 'config.php';

echo "<h2>Adding Admin Role Support</h2>";

try {
    // Add role column to users table
    $add_role_column = "ALTER TABLE users ADD COLUMN role ENUM('user','admin') DEFAULT 'user'";
    
    if ($conn->query($add_role_column) === TRUE) {
        echo "<p style='color: green;'>✅ Role column added successfully!</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Role column might already exist: " . $conn->error . "</p>";
    }

    // Update admin user to have admin role
    $update_admin = "UPDATE users SET role = 'admin' WHERE username = 'admin'";
    
    if ($conn->query($update_admin) === TRUE) {
        echo "<p style='color: green;'>✅ Admin user role updated!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error updating admin role: " . $conn->error . "</p>";
    }

    // Show current users
    echo "<h3>Current Users:</h3>";
    $users = $conn->query("SELECT id, username, email, role FROM users");
    if ($users->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr>";
        while($user = $users->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['username'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "<td>" . ($user['role'] ?? 'user') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    echo "<hr>";
    echo "<p><strong>Setup Complete!</strong></p>";
    echo "<p><a href='login.php' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Login as Admin</a></p>";
    echo "<p><a href='index.php' style='background: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Homepage</a></p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?>
