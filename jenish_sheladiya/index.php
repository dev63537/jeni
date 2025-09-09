<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Homepage</title>
  <link rel="stylesheet" href="css/base.css" />
</head>

<body>

  <nav class="navbar">
    <div class="nav-container">
      <a href="index.php" class="nav-logo">YourSite</a>
      <ul class="nav-menu">
        <li class="nav-item"><a href="index.php" class="nav-link active">Home</a></li>
        <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
        <li class="nav-item"><a href="order.php" class="nav-link">Order Now</a></li>
        <li class="nav-item"><a href="about.php" class="nav-link">About Us</a></li>
        <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
          <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
          <li class="nav-item"><a href="signup.php" class="nav-link">Sign Up</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>

  <!-- Main homepage content -->
  <div style="max-width: 900px; margin: 40px auto; padding: 20px; text-align: center;">
  </div>

</body>

</html>