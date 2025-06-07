<?php
session_start();
if (isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$user = [
    "name" => $_SESSION["username"] ?? "epa",
    "email" => $_SESSION["email"] ?? "admin@unimas.my",
    "phone" => $_SESSION["phone"] ?? "012-3456789",
    "role" => $_SESSION["role"] ?? "admin",
];
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
        <a href = "admin_profile.php" class="hellouser"> HELLO ADMIN !</a>
    </div>

    <a href="#" class="menuall">
        <img src="layout.png" class="menu">
        <span class="dash-text">DASHBOARD</span>
    </a>
    
    <a href="#">
        <img src="list.png" class="orders">
        <span class="dash-text">PRODUCTS</span>
    </a>
  
    <a href="#">
        <img src="card plus.png" class="billing">
        <span class="dash-text">EMPLOYEES</span>
    </a>

    <a href="#">
        <img src="gps.png" class="trackOrders">
        <span class="dash-text">SALES</span>
    </a>

    <a href="#">
        <img src="gps.png" class="trackOrders">
        <span class="dash-text">FINANCIAL RECORD</span>
    </a>

    <a href="#">
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
        <a href="#">HOME</a>
        <a href="#">MENU</a>
        <a href="#">ABOUT</a>
        <a href="#">REVIEWS</a>
    </nav>

    <div class="icons">
        <input type="text" placeholder=" 🔍︎ Search" class="search-box">
        <img src="cart.png" alt="cart" class="cart-img">
        <img src="account.png" alt="account" class="acc-img">
        <span class="icon"></span>
    </div>
</header>

<!--Admin Profile -->
<section class="admin-profile-section">
    <div class="admin-profile-box">
        <h2>Admin Profile</h2>
            <p><strong>Name:</strong> <span id="display-name"><?= htmlspecialchars($user['name']) ?></span></p>
            <p><strong>Email:</strong> <span id="display-email"><?= htmlspecialchars($user['email']) ?></span></p>
            <p><strong>Phone:</strong> <span id="display-phone"><?= htmlspecialchars($user['phone']) ?></span></p>
            <button id="edit-btn">Edit Profile</button>

        <!-- Edit Form -->
        <form id="edit-form" action="update_profile.php" method="POST" style="display:none;">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required><br>

            <button type="submit">Save</button>
            <button type="button" id="cancel-btn">Cancel</button>
        </form>
    </div>
</section>

    <!--Link to JavaScript-->
    <script src="profile.js"></script>
    <script src="admin.js"></script>
