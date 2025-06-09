<<<<<<< HEAD
helloo
olaaa test2w1e cx7sjx..
=======
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>

    <!-- Sidebar -->
<div id="sidebar" class="sidebar">

    <div class="admin-header">
        <img src="account.png" alt="Admin Image"  class="acc-dash">
        <a href = "admin_dashboard.php" class="hellouser"> HELLO ADMIN !</a>
    </div>

    <a href="admin_product.php" class="menuall">
        <img src="layout.png" class="products">
        <span class="dash-text">PRODUCTS</span>
    </a>
    
    <a href="admin_employee.php">
        <img src="employees.png" class="employees">
        <span class="dash-text">EMPLOYEES</span>
    </a>
  
    <a href="admin_sales.php">
        <img src="sales.png" class="sales">
        <span class="dash-text">SALES</span>
    </a>

    <a href="admin_financialRecord.php">
        <img src="financialrecord.png" class="financialRecord">
        <span class="dash-text">FINANCIAL RECORD</span>
    </a>

    <a href="admin_profile.php">
        <img src="profile2.png" class="profile">
        <span class="dash-text">PROFILE</span>
    </a>

    <a href="#">
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
        <a href="admin_dashboard.php">DASHBOARD</a>
        <a href="#">ABOUT</a>
        <a href="#">REVIEWS</a>
    </nav>

    <div class="icons">
        <input type="text" placeholder=" 🔍︎ Search" class="search-box">
        <a href="admin_setting.php">
        <img src="settings.png" alt="setting" class="setting-img"></a>
        <a href="admin_noti.php">
        <img src="notification.png" alt="noti" class="noti-img"></a>
        <a href="admin_profile.php">
        <img src="account.png" alt="account" class="acc-img"></a>
        <span class="icon"></span>
    </div>
</header>



    <!--Link to JavaScript-->
    <script src="profile.js"></script>
    <script src="admin.js"></script>
>>>>>>> 29df92b8c5c630b519c84901825685acec38beb3
