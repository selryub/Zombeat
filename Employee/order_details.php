<?php
require "../admin/db_connect.php";
include "employee_frame.php";

$user_email = $_SESSION['email'] ?? 'user@example.com';
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
      justify-content: space-around;
      align-items: center;
      margin-bottom: 30px;
    }

    .location-box {
      text-align: center;
    }

    .location-box img {
      width: 100px;
      height: 100px;
      border-radius: 8px;
      object-fit: cover;
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
<div class="location-section" style="text-align: center; display: flex; justify-content: center; gap: 60px; align-items: center; margin-bottom: 30px;">
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
        <div class="detail-row"><span class="detail-label">Order ID:</span><span class="detail-value" id="orderId"></span></div>
        <div class="detail-row"><span class="detail-label">Date & Time:</span><span class="detail-value"><?= date('Y-m-d H:i:s') ?></span></div>
        <div class="detail-row"><span class="detail-label">Payment Method:</span><span class="detail-value" id="paymentMethod">-</span></div>
        <div class="detail-row"><span class="detail-label">Order Type:</span><span class="detail-value" id="orderType">-</span></div>
      </div>

      <h3 class="subsection-title">ORDER SUMMARY</h3>
      <div class="order-summary">
        <div class="summary-row"><span class="summary-label">Subtotal:</span><span class="summary-value" id="summarySubtotal">RM 0.00</span></div>
        <div class="summary-row"><span class="summary-label">Delivery Fee:</span><span class="summary-value" id="summaryDelivery">RM 0.00</span></div>
        <div class="summary-divider"></div>
        <div class="total-row"><span class="total-label">TOTAL:</span><span class="total-value" id="summaryTotal">RM 0.00</span></div>
      </div>

      <div class="final-remarks">
        <label class="remarks-label">Remarks:</label>
        <div class="remarks-display" id="remarksDisplay"></div>
      </div>

  <!-- Button -->
  <a href="../REGISTERED MEMBER/user_dashboard.php" class="btn-order-again">ORDER AGAIN</a>
</div>

</body>
</html>

