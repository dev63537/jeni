<?php
session_start();
require 'config.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id <= 0) { header('Location: order.php'); exit(); }

$stmt = $conn->prepare("SELECT id, customer_name, amount FROM orders WHERE id = ?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();
if (!$order) { header('Location: order.php'); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // For COD, we don't take payment now. Mark payment_status as unpaid and log a payments row with status failed? Better: no row, but we can log cod selection.
    $note_txn = 'COD' . time() . rand(100,999);
    // Optionally record a payments entry with method cod and status success (acknowledge selection)
    $stmt = $conn->prepare("INSERT INTO payments (order_id, amount, method, status, transaction_id) VALUES (?, ?, 'cod', 'success', ?)");
    $amt = (float)($order['amount'] ?? 0);
    $stmt->bind_param('ids', $order_id, $amt, $note_txn);
    $stmt->execute();
    $stmt->close();

    // Keep payment_status as unpaid for COD collection on delivery
    $status = 'unpaid';
    $stmt = $conn->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $order_id);
    $stmt->execute();
    $stmt->close();

    header('Location: cod_selected.php?order_id=' . $order_id . '&txn=' . $note_txn);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirm COD - Shyam Enterprise</title>
  <link rel="stylesheet" href="css/base.css" />
  <style>.card{max-width:620px;margin:0 auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.08)}</style>
</head>
<body>
  <nav class="navbar">
    <div class="nav-container">
      <a href="index.php" class="nav-logo">Shyam Enterprise</a>
    </div>
  </nav>
  <header class="page-hero">
    <div class="container">
      <h1>Cash on Delivery</h1>
      <p>Confirm COD selection for your order</p>
    </div>
  </header>
  <div class="order-container">
    <div class="card">
      <p><strong>Order:</strong> #<?php echo $order['id']; ?></p>
      <p><strong>Amount Payable on Delivery:</strong> ₹ <?php echo number_format((float)$order['amount'],2); ?></p>
      <form method="POST">
        <button style="background:#0e66cc;color:#fff;padding:12px 16px;border:none;border-radius:8px;cursor:pointer">Confirm COD</button>
      </form>
    </div>
  </div>
</body>
</html>
