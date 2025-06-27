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
    <title>Employee - Dashboard</title>
    <link rel="stylesheet" href="employeestyle.css">
</head>
<body>

    <!-- Sidebar -->
<div id="sidebar" class="sidebar">

    <div class="employee-header">
        <img src="img/account.png" alt="Employee Image"  class="acc-dash">
        <a href = "employee_dashboard.php" class="hellouser"> HELLO PART_TIMER !</a>
    </div>

    <a href="order_details.php" class="menuall">
        <img src="img/details.png" class="products">
        <span class="dash-text">ORDER DETAILS</span>
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

    <a href="../admin/logout.php" onclick="return confirmLogout()">
        <img src="img/logout.png" class="logout">
        <span class="dash-text">LOGOUT</span>
    </a>
</div>

<!-- Header -->
<header class="navbar">
<div class="left-header">
    <div class="menu-icon" onclick="toggleSidebar()">☰</div>
    <img src="img/kiosk.jpg" alt="Logo" class="logo-img">
    <div class="logo-text">FCSIT KIOSK - Project Demo</div>
</div>

    <nav>
        <a href="product.php">PRODUCT</a>
        <a href="about.php">ABOUT</a>
        <a href="review.php">REVIEWS</a>
    </nav>

    <div class="icons">
        <div class="settings-container">
            <img src="img/settings.png" alt="Settings" class="setting-img" id="settings-toggle">    
        </div>
        <a href="profile_page.php">
            <img src="img/account.png" alt="account" class="acc-img"></a>
        <span class="icon"></span>
    </div>
</header>
<script>
function confirmLogout() {
    return confirm("Are you sure you want to log out?");
}
</script>

    <!--Link to JavaScript-->
    <script src="employee.js"></script>


