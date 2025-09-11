<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
$user_id = $_SESSION['user_id'];
$me = $conn->prepare("SELECT role FROM users WHERE id = ?");
$me->bind_param('i', $user_id);
$me->execute();
$me->bind_result($role);
$me->fetch();
$me->close();
if (!in_array($role, ['admin','manager'], true)) { header('Location: dashboard.php'); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $oid = intval($_POST['order_id']);
    $new = $_POST['new_status'];
    $allowed = ['pending','in_progress','completed','cancelled'];
    if (in_array($new, $allowed, true)) {
        $up = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $up->bind_param('si', $new, $oid);
        $up->execute();
        $up->close();
    }
    header('Location: admin_orders.php');
    exit();
}

$orders = $conn->query("SELECT id, customer_name, email, phone, service_type, quantity, order_date, status, payment_status, amount, reference_image FROM orders ORDER BY order_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Orders - Admin</title>
  <link rel="stylesheet" href="css/base.css" />
  <link rel="stylesheet" href="css/dashboard.css" />
  <style>
    .table{width:100%;border-collapse:collapse}
    .table th,.table td{padding:10px;border-bottom:1px solid #eee;text-align:left;vertical-align:top}
    .sel{padding:6px 8px;border:1px solid #ddd;border-radius:6px}
    .btn{padding:6px 10px;border:none;border-radius:6px;background:#0e66cc;color:#fff;cursor:pointer}
    .wrap{background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.1)}
    .thumb{width:56px;height:56px;object-fit:cover;border-radius:6px;border:1px solid #eee}
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="nav-container">
      <a href="index.php" class="nav-logo">Shyam Enterprise</a>
      <ul class="nav-menu">
        <li class="nav-item"><a href="admin_panel.php" class="nav-link">Admin Panel</a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
      </ul>
    </div>
  </nav>

  <header class="page-hero">
    <div class="container">
      <h1>Manage Orders</h1>
      <p>Update order and payment statuses</p>
    </div>
  </header>

  <div class="dashboard-container">
    <div class="wrap">
      <table class="table">
        <thead><tr><th>#</th><th>Customer</th><th>Service</th><th>Qty</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th>Ref Image</th><th>Action</th></tr></thead>
        <tbody>
        <?php while ($o = $orders->fetch_assoc()): ?>
          <tr>
            <td>#<?php echo $o['id']; ?></td>
            <td><?php echo htmlspecialchars($o['customer_name']); ?><br><small><?php echo htmlspecialchars($o['phone']); ?></small></td>
            <td><?php echo htmlspecialchars($o['service_type']); ?></td>
            <td><?php echo (int)$o['quantity']; ?></td>
            <td>₹ <?php echo number_format((float)$o['amount'],2); ?></td>
            <td><?php echo strtoupper($o['payment_status']); ?></td>
            <td><?php echo ucfirst($o['status']); ?></td>
            <td><?php echo date('M j, Y', strtotime($o['order_date'])); ?></td>
            <td>
              <?php if (!empty($o['reference_image'])): ?>
                <a href="<?php echo htmlspecialchars($o['reference_image']); ?>" target="_blank">
                  <img class="thumb" src="<?php echo htmlspecialchars($o['reference_image']); ?>" alt="ref">
                </a>
              <?php else: ?>
                <span style="color:#888">None</span>
              <?php endif; ?>
            </td>
            <td>
              <form method="POST">
                <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                <select class="sel" name="new_status">
                  <?php foreach (['pending','in_progress','completed','cancelled'] as $s): ?>
                  <option value="<?php echo $s; ?>" <?php if ($o['status']===$s) echo 'selected'; ?>><?php echo ucfirst($s); ?></option>
                  <?php endforeach; ?>
                </select>
                <button class="btn" type="submit">Save</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
