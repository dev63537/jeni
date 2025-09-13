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

// Generate a pseudo QR image URL (use a placeholder service)
$qr_text = urlencode('UPI:order#' . $order['id'] . ' amount=' . $order['amount']);
$qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' . $qr_text;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UPI Payment - Shyam Enterprise</title>
  <link rel="stylesheet" href="css/base.css" />
  <style>
    .gateway{max-width:520px;margin:0 auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.08);text-align:center}
    .qr{margin:12px auto;display:block;border:8px solid #f8f9fa;border-radius:12px}
    .msg{color:#28a745;font-weight:700;margin-top:10px;display:none}
    .timer{color:#666;margin-top:6px}
    .btn{background:#28a745;color:#fff;padding:10px 14px;border-radius:8px;text-decoration:none;display:none}
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
      <h1>Pay via UPI</h1>
      <p>Scan the QR with your UPI app to complete payment</p>
    </div>
  </header>

  <div class="order-container">
    <div class="gateway">
      <h3>Order #<?php echo $order['id']; ?> • ₹ <?php echo number_format((float)$order['amount'], 2); ?></h3>
      <img class="qr" src="<?php echo $qr_url; ?>" width="180" height="180" alt="UPI QR">
      <div class="timer">Waiting for payment... <span id="count">7</span>s</div>
      <div id="paid" class="msg">Payment Done ✅</div>
      <a id="cont" class="btn" href="#">Continue</a>
    </div>
  </div>

  <form id="proc" action="payment_process.php" method="POST" style="display:none">
    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
    <input type="hidden" name="method" value="upi">
    <input type="hidden" name="outcome" value="success">
  </form>

  <script>
    (function(){
      var n = 7, s = document.getElementById('count');
      var paid = document.getElementById('paid');
      var cont = document.getElementById('cont');
      var form = document.getElementById('proc');
      var t = setInterval(function(){
        n--; s.textContent = n;
        if (n <= 0) {
          clearInterval(t);
          paid.style.display = 'block';
          cont.style.display = 'inline-block';
          cont.textContent = 'View Receipt';
          // Submit to create success payment, then redirect will land on receipt
          form.submit();
        }
      }, 1000);
    })();
  </script>
</body>
</html>
