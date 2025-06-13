<?php
session_start();

if (isset($_SESSION["username"]) && $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Order Detail</title>
  <link rel="stylesheet" href="employeestyle.css"> 
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f4ff;
      margin: 0;
      padding: 0;
    }
    .track-box {
      width: 90%;
      max-width: 800px;
      background: #cbd8f7;
      margin: 50px auto;
      padding: 30px;
      border-radius: 15px;
      text-align: center;
    }
    .track-box h2 {
      margin-bottom: 20px;
      font-size: 24px;
    }
    .status-line {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 30px 0;
    }
    .status-step {
      width: 18%;
      position: relative;
      text-align: center;
    }
    .status-step:before {
      content: "";
      height: 5px;
      background-color: #000;
      position: absolute;
      top: 18px;
      left: -50%;
      width: 100%;
      z-index: -1;
    }
    .status-step:first-child:before {
      display: none;
    }
    .status-step img {
      width: 30px;
      margin-bottom: 10px;
    }
    .order-id,
    .estimated-time {
      margin: 15px 0;
      font-size: 18px;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="track-box">
  <h2>ORDER STATUS</h2>
  <p class="order-id">Order ID: <strong>#ORDER12345</strong></p>

  <div class="status-line">
    <div class="status-step">
      <img src="../employee/notification.png" alt="Received">
      <p>ORDER RECEIVED</p>
    </div>
    <div class="status-step">
      <img src="../employee/confirm.png" alt="Confirmed">
      <p>ORDER CONFIRMED</p>
    </div>
    <div class="status-step">
      <img src="../employee/processing.png" alt="Processing">
      <p>ORDER PROCESSED</p>
    </div>
    <div class="status-step">
      <img src="../employee/ontheway.png" alt="On the Way">
      <p>ON THE WAY</p>
    </div>
    <div class="status-step">
      <img src="../employee/delivered.png" alt="Delivered">
      <p>DELIVERED</p>
    </div>
  </div>

  <p class="estimated-time">ESTIMATED TIME: <strong>12:30 PM</strong></p>
</div>

</body>
</html>
