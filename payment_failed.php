<?php
session_start();
require 'config.php';

$order_id = intval($_GET['order_id'] ?? 0);
$txn = $_GET['txn'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Failed</title>
  <link rel="stylesheet" href="css/base.css" />
  <style>
    .receipt{max-width:700px;margin:0 auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.08)}
    .fail{color:#c0392b;font-weight:700}
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="nav-container">
      <a href="index.php" class="nav-logo">Shyam Enterprise</a>
    </div>
  </nav>
  <header class="page-hero">
    <div class="container">
      <h1 class="fail">Payment Failed</h1>
      <p>Your payment could not be completed.</p>
    </div>
  </header>
  <div class="order-container">
    <div class="receipt">
      <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($order_id); ?></p>
      <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($txn); ?></p>
      <div style="margin-top:16px;">
        <a class="btn" style="background:#e74c3c;color:#fff;padding:10px 14px;border-radius:8px;text-decoration:none" href="checkout.php?order_id=<?php echo $order_id; ?>">Try Again</a>
      </div>
    </div>
  </div>
</body>
</html>
