<?php
require "../admin/db_connect.php";
include "employee_frame.php";

// Get order data from session
$order = $_SESSION['order_data'] ?? [];
$user_name = $_SESSION['username'] ?? 'Customer';
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
    }
    .close-btn {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      color: #888;
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
    .total-row {
      font-size: 18px;
      margin-top: 10px;
      color: #1a1a1a;
    }
    .summary-label, .total-label {
      font-weight: bold;
    }
    .summary-divider {
      border-top: 1px solid #ccc;
      margin: 10px 0;
    }
    .remarks-display {
      background: #f1f5fc;
      padding: 10px;
      border-radius: 6px;
      color: #333;
      min-height: 40px;
    }
    .cart-item {
      display: flex;
      align-items: center;
      margin: 10px 0;
      border-bottom: 1px dashed #ccc;
      padding-bottom: 10px;
    }
    .item-image img {
      border-radius: 10px;
    }
    .item-details {
      flex: 1;
      margin-left: 15px;
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

  <!-- 📍 Location -->
  <div class="location-section">
    <div class="location-box">
      <div style="font-size: 40px;">📍</div>
      <p>Stall Location</p>
    </div>
    <div style="font-size: 24px;">➝</div>
    <div class="location-box">
      <div style="font-size: 40px;">📍</div>
      <p><?= ($order['deliveryOption'] ?? '') === 'Delivery' ? 'Customer Location' : 'Pickup Point' ?></p>
    </div>
  </div>

  <!-- ✅ Order Info -->
  <div class="order-info-section">
    <div class="section-header">
      <h2 class="section-title">ORDER INFO</h2>
      <button class="close-btn" onclick="window.location.href='../REGISTERED MEMBER/user_dashboard.php'">×</button>
    </div>

    <div class="order-details">
      <div class="detail-row"><span class="detail-label">Customer Name:</span><span class="detail-value"><?= htmlspecialchars($order['username'] ?? $user_name) ?></span></div>
      <div class="detail-row"><span class="detail-label">Order ID:</span><span class="detail-value"><?= htmlspecialchars($order['orderId'] ?? '-') ?></span></div>
      <div class="detail-row"><span class="detail-label">Date & Time:</span><span class="detail-value"><?= htmlspecialchars($order['dateTime'] ?? date('Y-m-d H:i:s')) ?></span></div>
      <div class="detail-row"><span class="detail-label">Payment Method:</span><span class="detail-value"><?= htmlspecialchars($order['paymentMethod'] ?? '-') ?></span></div>
      <div class="detail-row"><span class="detail-label">Order Type:</span><span class="detail-value"><?= htmlspecialchars($order['deliveryOption'] ?? '-') ?></span></div>
      <?php if (!empty($order['deliveryAddress'])): ?>
      <div class="detail-row"><span class="detail-label">Delivery Address:</span><span class="detail-value"><?= htmlspecialchars($order['deliveryAddress']) ?></span></div>
      <?php endif; ?>
    </div>

    <!-- ✅ Cart Items -->
    <?php if (!empty($order['items']) && is_array($order['items'])): ?>
      <h3 class="subsection-title">CART ITEMS</h3>
      <div class="cart-list">
        <?php foreach ($order['items'] as $item): ?>
          <div class="cart-item">
            <div class="item-image">
              <img src="<?= htmlspecialchars($item['image'] ?? 'img/no-image.png') ?>" width="60" height="60" alt="<?= htmlspecialchars($item['name'] ?? 'Item') ?>">
            </div>
            <div class="item-details">
              <div><strong><?= htmlspecialchars($item['name'] ?? '-') ?></strong></div>
              <div>Quantity: <?= htmlspecialchars($item['quantity'] ?? 1) ?></div>
              <div>Price: RM <?= number_format($item['price'] ?? 0, 2) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- ✅ Summary -->
    <h3 class="subsection-title">ORDER SUMMARY</h3>
    <div class="order-summary">
      <div class="summary-row"><span class="summary-label">Subtotal:</span><span class="summary-value">RM <?= number_format($order['subtotal'] ?? 0, 2) ?></span></div>
      <div class="summary-row"><span class="summary-label">Delivery Fee:</span><span class="summary-value">RM <?= number_format($order['deliveryFee'] ?? 0, 2) ?></span></div>
      <div class="summary-divider"></div>
      <div class="total-row"><span class="total-label">TOTAL:</span><span class="total-value">RM <?= number_format($order['total'] ?? 0, 2) ?></span></div>
    </div>

    <!-- ✅ Remarks -->
    <div class="final-remarks">
      <label class="remarks-label">Remarks:</label>
      <div class="remarks-display"><?= htmlspecialchars($order['remarks'] ?? 'No remarks') ?></div>
    </div>

    <!-- ✅ Update Button -->
    <a href="../track_order.php" class="btn-order-again">UPDATE</a>
  </div>

</div>

</body>
</html>
