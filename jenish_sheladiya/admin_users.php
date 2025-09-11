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
$can_edit_roles = ($role === 'admin');
if (!in_array($role, ['admin','manager'], true)) { header('Location: dashboard.php'); exit(); }

if ($can_edit_roles && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['target_id'], $_POST['new_role'])) {
    $target = intval($_POST['target_id']);
    $new_role = in_array($_POST['new_role'], ['user','manager','admin'], true) ? $_POST['new_role'] : 'user';
    if ($target !== $user_id) {
        $up = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $up->bind_param('si', $new_role, $target);
        $up->execute();
        $up->close();
    }
    header('Location: admin_users.php');
    exit();
}

$users = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users - Admin</title>
  <link rel="stylesheet" href="css/base.css" />
  <link rel="stylesheet" href="css/dashboard.css" />
  <style>
    .table{width:100%;border-collapse:collapse}
    .table th,.table td{padding:10px;border-bottom:1px solid #eee;text-align:left}
    .role-form{display:inline}
    .sel{padding:6px 8px;border:1px solid #ddd;border-radius:6px}
    .btn{padding:6px 10px;border:none;border-radius:6px;background:#0e66cc;color:#fff;cursor:pointer}
    .wrap{background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.1)}
    .muted{color:#777}
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
      <h1>Manage Users</h1>
      <p><?php echo $can_edit_roles ? 'Add/remove admin or manager role' : 'View users (manager)'; ?></p>
    </div>
  </header>

  <div class="dashboard-container">
    <div class="wrap">
      <table class="table">
        <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Joined</th><th>Action</th></tr></thead>
        <tbody>
        <?php while ($u = $users->fetch_assoc()): ?>
          <tr>
            <td>#<?php echo $u['id']; ?></td>
            <td><?php echo htmlspecialchars($u['username']); ?></td>
            <td><?php echo htmlspecialchars($u['email']); ?></td>
            <td><?php echo $u['role']; ?></td>
            <td><?php echo date('M j, Y', strtotime($u['created_at'])); ?></td>
            <td>
              <?php if ($can_edit_roles): ?>
              <form class="role-form" method="POST">
                <input type="hidden" name="target_id" value="<?php echo $u['id']; ?>">
                <select class="sel" name="new_role">
                  <?php foreach (['user'=>'User','manager'=>'Manager','admin'=>'Admin'] as $val=>$label): ?>
                    <option value="<?php echo $val; ?>" <?php if ($u['role']===$val) echo 'selected'; ?>><?php echo $label; ?></option>
                  <?php endforeach; ?>
                </select>
                <button class="btn" type="submit">Update</button>
              </form>
              <?php else: ?>
                <span class="muted">No edit permission</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
