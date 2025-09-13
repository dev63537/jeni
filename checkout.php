<?php
session_start();
require 'config.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id <= 0) {
    header('Location: order.php');
    exit();
}

$stmt = $conn->prepare("SELECT id, customer_name, email, phone, service_type, quantity, urgent_order, amount, payment_status, order_date FROM orders WHERE id = ?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    header('Location: order.php');
    exit();
}

// Simple pricing estimation if amount is not set
if ($order['amount'] === null) {
    $base = 500; // base amount
    $per_unit = 200; // per quantity
    $urgent_fee = $order['urgent_order'] ? 250 : 0;
    $amount = $base + ($order['quantity'] * $per_unit) + $urgent_fee;
    $stmt = $conn->prepare("UPDATE orders SET amount = ? WHERE id = ?");
    $stmt->bind_param('di', $amount, $order_id);
    $stmt->execute();
    $stmt->close();
    $order['amount'] = $amount;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout - Shyam Enterprise</title>
  <link rel="stylesheet" href="css/base.css" />
  <link rel="stylesheet" href="css/order.css" />
  <style>
    .checkout-card{background:#fff;padding:24px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.08);max-width:720px;margin:0 auto}
    .summary{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin:12px 0}
    .summary div{background:#f7f9fc;border-radius:8px;padding:10px}
    .total{font-size:1.4rem;font-weight:700;margin-top:10px}
    .pay-actions{margin-top:18px}
    .pay-btn{background:#28a745;color:#fff;padding:12px 18px;border-radius:8px;text-decoration:none;display:inline-block}
    .methods{margin-top:12px;display:flex;gap:10px}
    .method{display:inline-block;background:#f0f1ff;color:#4a54e1;padding:10px 14px;border-radius:8px;text-decoration:none}
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="nav-container">
      <a href="index.php" class="nav-logo">Shyam Enterprise</a>
      <ul class="nav-menu">
        <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
        <li class="nav-item"><a href="order.php" class="nav-link">Order Now</a></li>
        <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
      </ul>
    </div>
  </nav>

  <header class="page-hero">
    <div class="container">
      <h1>Checkout</h1>
      <p>Review your order and choose a payment method</p>
    </div>
  </header>

  <div class="order-container">
    <div class="checkout-card">
      <h3>Order #<?php echo $order['id']; ?></h3>
      <div class="summary">
        <div><strong>Customer</strong><br><?php echo htmlspecialchars($order['customer_name']); ?></div>
        <div><strong>Service</strong><br><?php echo htmlspecialchars($order['service_type']); ?></div>
        <div><strong>Quantity</strong><br><?php echo (int)$order['quantity']; ?></div>
        <div><strong>Urgent</strong><br><?php echo $order['urgent_order'] ? 'Yes' : 'No'; ?></div>
      </div>
      <div class="total">Total: ₹ <?php echo number_format((float)$order['amount'], 2); ?></div>
      <div class="methods">
        <a class="method" href="mock_payment_gateway.php?order_id=<?php echo $order['id']; ?>&method=card">Pay by Card</a>
        <a class="method" href="upi_payment.php?order_id=<?php echo $order['id']; ?>">Pay by UPI</a>
        <a class="method" href="cod_confirm.php?order_id=<?php echo $order['id']; ?>">Cash on Delivery (COD)</a>
      </div>
    </div>
  </div>
</body>
</html>
