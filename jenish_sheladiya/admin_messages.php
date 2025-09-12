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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['msg_id'], $_POST['new_status'])) {
    $mid = intval($_POST['msg_id']);
    $new = $_POST['new_status'];
    $allowed = ['new','read','replied'];
    if (in_array($new, $allowed, true)) {
        $up = $conn->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
        $up->bind_param('si', $new, $mid);
        $up->execute();
        $up->close();
    }
    header('Location: admin_messages.php');
    exit();
}

$msgs = $conn->query("SELECT id, name, email, phone, subject, created_at, status FROM contact_messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Messages - Admin</title>
  <link rel="stylesheet" href="css/base.css" />
  <link rel="stylesheet" href="css/dashboard.css" />
  <style>
    .table{width:100%;border-collapse:collapse}
    .table th,.table td{padding:10px;border-bottom:1px solid #eee;text-align:left}
    .sel{padding:6px 8px;border:1px solid #ddd;border-radius:6px}
    .btn{padding:6px 10px;border:none;border-radius:6px;background:#0e66cc;color:#fff;cursor:pointer}
    .wrap{background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.1)}
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
      <h1>Manage Messages</h1>
      <p>Track contact inquiries and update their status</p>
    </div>
  </header>

  <div class="dashboard-container">
    <div class="wrap">
      <table class="table">
        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Subject</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
        <?php while ($m = $msgs->fetch_assoc()): ?>
          <tr>
            <td>#<?php echo $m['id']; ?></td>
            <td><?php echo htmlspecialchars($m['name']); ?></td>
            <td><?php echo htmlspecialchars($m['email']); ?></td>
            <td><?php echo htmlspecialchars($m['phone']); ?></td>
            <td><?php echo htmlspecialchars($m['subject']); ?></td>
            <td><?php echo ucfirst($m['status']); ?></td>
            <td><?php echo date('M j, Y', strtotime($m['created_at'])); ?></td>
            <td>
              <form method="POST">
                <input type="hidden" name="msg_id" value="<?php echo $m['id']; ?>">
                <select class="sel" name="new_status">
                  <?php foreach (['new','read','replied'] as $s): ?>
                  <option value="<?php echo $s; ?>" <?php if ($m['status']===$s) echo 'selected'; ?>><?php echo ucfirst($s); ?></option>
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
