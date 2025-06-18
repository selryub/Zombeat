<?php
require "db_connect.php";
require "../admin/db_connect.php"
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

    .order-box {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 20px;
    }

    .order-box table {
      width: 100%;
      border-collapse: collapse;
    }

    .order-box td {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }

    .order-box tr:last-child td {
      border-bottom: none;
    }

    .order-summary {
      text-align: right;
      margin-top: 15px;
      font-weight: bold;
      font-size: 16px;
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

<?php include "employee_frame.php"; ?>

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
  <div class="order-box">
    <h3>Order Details</h3>
    <table>
      <tr>
        <td>1x Nas Lemak</td>
        <td style="text-align:right;">RM 6.00</td>
      </tr>
      <tr>
        <td>1x Nasi Goreng</td>
        <td style="text-align:right;">RM 4.00</td>
      </tr>
      <tr>
        <td>1x Mi Goreng Telur Mata</td>
        <td style="text-align:right;">RM 5.50</td>
      </tr>
      <tr>
        <td>1x Mi Jawa</td>
        <td style="text-align:right;">RM 6.00</td>
      </tr>
      <tr>
        <td>1x Kuih Apam Cheese</td>
        <td style="text-align:right;">RM 3.50</td>
      </tr>
    </table>

    <div class="order-summary">
      Total = RM 25.00
    </div>
  </div>

  <!-- Button -->
  <a href="/Zombeat/REGISTERED MEMBER/order.php" class="btn-order-again">ORDER AGAIN</a>
</div>

</body>
</html>

