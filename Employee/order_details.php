<?php
require "../admin/db_connect.php";
include "employee_frame.php";
session_start();

$user_email = $_SESSION['email'] ?? 'user@example.com';
$user_name = $_SESSION['username'] ?? 'Customer';

$order_id = $_GET['order_id'] ?? 0;
$order = null;

if ($order_id) {
  $stmt = $conn->prepare("SELECT * FROM `order` WHERE order_id = ?");
  $stmt->bind_param("i", $order_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $order = $result->fetch_assoc();
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Order Detail</title>
  <link rel="stylesheet" href="employeestyle.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #d4e3fc;
      margin: 0;
      padding: 0;
    }

    .order-wrapper {
      max-width: 1000px;
      margin: 40px auto;
      padding: 30px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .location-section {
      display: flex;
      justify-content: center;
      gap: 60px;
      align-items: center;
      margin-bottom: 30px;
    }

    .location-box {
      text-align: center;
    }

    .order-info-section {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 20px;
      background-color: #f9fbff;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .section-title {
      font-size: 20px;
      font-weight: bold;
      color: #2c3e50;
      margin: 0;
    }

    .close-btn {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      color: #888;
    }

    .order-details {
      margin-bottom: 20px;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      padding: 6px 0;
      font-size: 16px;
      border-bottom: 1px dashed #ccc;
    }

    .detail-label {
      font-weight: bold;
      color: #333;
    }

    .detail-value {
      color: #444;
    }

    .subsection-title {
      margin-top: 20px;
      font-size: 18px;
      font-weight: bold;
      color: #2c3e50;
    }

    .summary-row, .total-row {
      display: flex;
      justify-content: space-between;
      padding: 6px 0;
      font-size: 16px;
    }

    .summary-label, .total-label {
      font-weight: bold;
    }

    .total-row {
      font-size: 18px;
      margin-top: 10px;
      color: #1a1a1a;
    }

    .summary-divider {
      border-top: 1px solid #ccc;
      margin: 10px 0;
    }

    .final-remarks {
      margin-top: 15px;
    }

    .remarks-label {
      font-weight: bold;
      display: block;
      margin-bottom: 6px;
    }

    .remarks-display {
      background: #f1f5fc;
      padding: 10px;
      border-radius: 6px;
      color: #333;
      min-height: 40px;
    }

    .btn-order-again {
      display: block;
      margin-top: 25px;
      width: 100%;
      background-color: #2c3e50;
      color: white;
      padding: 12px;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      text-align: center;
      text-decoration: none;
      transition: 0.3s ease;
    }

    .btn-order-again:hover {
      background-color: #1b2838;
    }
  </style>
</head>
<body>

<div class="order-wrapper">

  <!-- Location Icons -->
  <div class="location-section">
    <div class="location-box">
      <div style="font-size: 40px;">📍</div>
      <p>Stall Location</p>
    </div>
    <div style="font-size: 24px;">➝</div>
    <div class="location-box">
      <div style="font-size: 40px;">📍</div>
      <p>Customer Location</p>
    </div>
  </div>

  <!-- Order Details -->
  <div class="order-info-section">
    <div class="section-header">
      <h2 class="section-title">ORDER INFO</h2>
      <button class="close-btn">×</button>
    </div>

    <div class="order-details">
      <div class="detail-row"><span class="detail-label">Customer Name:</span><span class="detail-value"><?= htmlspecialchars($user_name) ?></span></div>
      <div class="detail-row"><span class="detail-label">Order ID:</span><span class="detail-value"><?= htmlspecialchars($order['order_id'] ?? '-') ?></span></div>
      <div class="detail-row"><span class="detail-label">Date & Time:</span><span class="detail-value"><?= htmlspecialchars($order['order_date'] ?? '-') ?></span></div>
      <div class="detail-row"><span class="detail-label">Payment Method:</span><span class="detail-value"><?= htmlspecialchars($order['payment_method'] ?? '-') ?></span></div>
      <div class="detail-row"><span class="detail-label">Order Type:</span><span class="detail-value"><?= htmlspecialchars($order['tracking_status'] ?? '-') ?></span></div>
    </div>

    <h3 class="subsection-title">ORDER SUMMARY</h3>
    <div class="order-summary">
      <div class="summary-row"><span class="summary-label">Subtotal:</span><span class="summary-value">RM <?= number_format($order['total_amount'] ?? 0, 2) ?></span></div>
      <div class="summary-row"><span class="summary-label">Delivery Fee:</span><span class="summary-value">RM 0.00</span></div>
      <div class="summary-divider"></div>
      <div class="total-row"><span class="total-label">TOTAL:</span><span class="total-value">RM <?= number_format($order['total_amount'] ?? 0, 2) ?></span></div>
    </div>

    <div class="final-remarks">
      <label class="remarks-label">Remarks:</label>
      <div class="remarks-display">N/A</div>
    </div>

    <a href="../REGISTERED MEMBER/track_order.php" class="btn-order-again">UPDATE</a>
  </div>

</div>

</body>
</html>

