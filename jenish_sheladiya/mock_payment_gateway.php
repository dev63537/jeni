<?php
session_start();
require 'config.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$method = isset($_GET['method']) ? $_GET['method'] : 'card';
if ($order_id <= 0) { header('Location: order.php'); exit(); }

$stmt = $conn->prepare("SELECT id, customer_name, amount FROM orders WHERE id = ?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();
if (!$order) { header('Location: order.php'); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mock Payment - Shyam Enterprise</title>
  <link rel="stylesheet" href="css/base.css" />
  <style>
    .gateway{max-width:520px;margin:0 auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.08)}
    .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    label{display:block;margin-bottom:6px;font-weight:600}
    input{width:100%;padding:10px;border:2px solid #e1e5e9;border-radius:8px}
    .pay{margin-top:16px}
    .btn{background:#28a745;color:#fff;padding:12px 16px;border-radius:8px;border:none;cursor:pointer}
    .note{color:#666;font-size:0.9rem;margin-top:8px}
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
      <h1>Card Payment</h1>
      <p>Enter card details to complete your payment</p>
    </div>
  </header>

  <div class="order-container">
    <div class="gateway">
      <h3>Pay for Order #<?php echo $order['id']; ?> • ₹ <?php echo number_format((float)$order['amount'], 2); ?></h3>
      <form action="payment_process.php" method="POST">
        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
        <input type="hidden" name="method" value="<?php echo htmlspecialchars($method); ?>">
        <input type="hidden" name="outcome" value="success">
        <div class="row">
          <div>
            <label>Card Number</label>
            <input type="text" name="card" placeholder="4111 1111 1111 1111" required>
          </div>
          <div>
            <label>Expiry</label>
            <input type="text" name="exp" placeholder="MM/YY" required>
          </div>
        </div>
        <div class="row">
          <div>
            <label>CVV</label>
            <input type="text" name="cvv" placeholder="123" required>
          </div>
          <div>
            <label>Name on Card</label>
            <input type="text" name="name" placeholder="Full Name" required>
          </div>
        </div>
        <div class="note">For demo purposes, any card details will be accepted.</div>
        <div class="pay">
          <button class="btn" type="submit">Pay Now</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
