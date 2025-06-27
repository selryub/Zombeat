<?php
session_start();

if (isset($_SESSION["user_id"]) && $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>

    <!-- Sidebar -->
<div id="sidebar" class="sidebar">

    <div class="admin-header">
        <img src="img/account.png" alt="Admin Image"  class="acc-dash">
        <a href = "admin_dashboard.php" class="hellouser"> HELLO ADMIN !</a>
    </div>

    <a href="admin_dashboard.php">
        <img src="img/sales.png" class="sales">
        <span class="dash-text">DASHBOARD</span>
    </a>

    <a href="admin_product.php" class="menuall">
        <img src="img/layout.png" class="products">
        <span class="dash-text">PRODUCTS</span>
    </a>
    
    <a href="admin_employee.php">
        <img src="img/employees.png" class="employees">
        <span class="dash-text">EMPLOYEES</span>
    </a>
  

    <a href="admin_financialRecord.php">
        <img src="img/financialrecord.png" class="financialRecord">
        <span class="dash-text">FINANCIAL RECORD</span>
    </a>

    <a href="admin_profile.php">
        <img src="img/profile2.png" class="profile">
        <span class="dash-text">PROFILE</span>
    </a>

    <a href="logout.php" onclick="return confirmLogout()">
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
        <a href="admin_dashboard.php">DASHBOARD</a>
        <a href="../about.php">ABOUT</a>
        <a href="../review.php">REVIEWS</a>
    </nav>

    <div class="icons">
        <div class="settings-container">
            <img src="img/settings.png" alt="Settings" class="setting-img" id="settings-toggle"></div>    
            <a href="admin_profile.php">
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
    <script src="admin.js"></script>


