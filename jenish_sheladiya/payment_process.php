<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: order.php'); exit(); }

$order_id = intval($_POST['order_id'] ?? 0);
$outcome = $_POST['outcome'] ?? 'failed';
$method = $_POST['method'] ?? 'card';

$stmt = $conn->prepare("SELECT id, amount FROM orders WHERE id = ?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) { header('Location: order.php'); exit(); }

$amount = (float)$order['amount'];
$transaction_id = strtoupper(substr($method,0,3)) . time() . rand(100,999);
$status = ($outcome === 'success') ? 'success' : 'failed';

// Record payment
$stmt = $conn->prepare("INSERT INTO payments (order_id, amount, method, status, transaction_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('idsss', $order_id, $amount, $method, $status, $transaction_id);
$stmt->execute();
$stmt->close();

// Update order payment status
$new_payment_status = ($status === 'success') ? 'paid' : 'failed';
$stmt = $conn->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
$stmt->bind_param('si', $new_payment_status, $order_id);
$stmt->execute();
$stmt->close();

if ($status === 'success') {
    header('Location: payment_success.php?order_id=' . $order_id . '&txn=' . $transaction_id);
} else {
    header('Location: payment_failed.php?order_id=' . $order_id . '&txn=' . $transaction_id);
}
exit();
