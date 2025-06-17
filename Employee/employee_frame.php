<?php
session_start();

if (isset($_SESSION["username"]) && $_SESSION["role"] !== "employee") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="employeestyle.css">
</head>
<body>

    <!-- Sidebar -->
<div id="sidebar" class="sidebar">

    <div class="employee-header">
        <img src="account.png" alt="Employee Image"  class="acc-dash">
        <a href = "employee_dashboard.php" class="hellouser"> HELLO EMPLOYEE !</a>
    </div>

    <a href="order_details.php" class="menuall">
        <img src="img/details.png" class="products">
        <span class="dash-text">ORDER DETAILS</span>
    </a>
    
    <a href="sales.php">
        <img src="img/sales.png" class="sales">
        <span class="dash-text">SALES</span>
    </a>
  
    <a href="schedule.php">
        <img src="img/schedule.png" class="schedule">
        <span class="dash-text">SCHEDULES</span>
    </a>

    <a href="product.php" class="menuall">
        <img src="img/layout.png" class="products">
        <span class="dash-text">PRODUCTS</span>
    </a>

    <a href="profile_page.php">
        <img src="img/profile2.png" class="profile">
        <span class="dash-text">PROFILE</span>
    </a>

    <a href="logout.php" onclick="return confirmLogout()">
        <img src="logout.png" class="logout">
        <span class="dash-text">LOGOUT</span>
    </a>
</div>

<!-- Header -->
<header class="navbar">
<div class="left-header">
    <div class="menu-icon" onclick="toggleSidebar()">☰</div>
    <img src="kiosk.jpg" alt="Logo" class="logo-img">
    <div class="logo-text">FCSIT KIOSK</div>
</div>

    <nav>
        <a href="employee_dashboard.php">DASHBOARD</a>
        <a href="about.php">ABOUT</a>
        <a href="review.html">REVIEWS</a>
    </nav>

    <div class="icons">
        <input type="text" placeholder=" 🔍︎ Search" class="search-box">
        <div class="settings-container">
            <img src="settings.png" alt="Settings" class="setting-img" id="settings-toggle">    
                </div>
        <a href="admin_noti.php">
            <img src="notification.png" alt="noti" class="noti-img"></a>
        <a href="profile_page.php">
            <img src="account.png" alt="account" class="acc-img"></a>
        <span class="icon"></span>
    </div>
</header>

    <!--Link to JavaScript-->
    <script src="employee.js"></script>


