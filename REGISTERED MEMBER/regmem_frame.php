<?php
session_start();

if (isset($_SESSION["username"]) && $_SESSION["role"] !== "registered member") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="regmemstyle.css">
</head>
<body>

    <!-- Sidebar -->
<div id="sidebar" class="sidebar">

    <div class="registered_member-header">
        <img src="img/account.png" alt="Registered Member Image"  class="acc-dash">
        <a href = "regmem_dashboard.php" class="hellouser"> HELLO USER !</a>
    </div>

    <a href="menu_page.php" class="menuall">
        <img src="#" class="menu">
        <span class="dash-text">MENU</span>
    </a>
    
    <a href="order.php">
        <img src="#" class="sales">
        <span class="dash-text">ORDERS</span>
    </a>
  
    <a href="billing.php">
        <img src="billing" class="billing">
        <span class="dash-text">BILLING</span>
    </a>

    <a href="track_order.html" class="menuall">
        <img src="#" class="track-orders">
        <span class="dash-text">TRACK ORDERS</span>
    </a>

    <a href="profile.php">
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
    <div class="logo-text">FCSIT KIOSK</div>
</div>

    <nav>
        <a href="../PUBLIC/index.php">HOME</a>
        <a href="menu_page.php">MENU</a>
        <a href="about.php">ABOUT</a>
        <a href="review.php">REVIEWS</a>
    </nav>
    <div class="icons">
    <!-- <input type="text" placeholder=" 🔍︎ Search" class="search-box"> -->
    <img src="img/cart.png" alt="cart" class="cart-img">
    <a href="/Zombeat/PUBLIC/login.php"><img src="img/account.png" alt="account" class="acc-img"></a>
    <span class="icon"></span>
  </div>

</header>


<script>
function confirmLogout() {
    return confirm("Are you sure you want to log out?");
}
</script>
    <!--Link to JavaScript-->
    <script src="regmem.js"></script>


