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
        <a href = "admin_dashboard.php" class="hellouser"> HELLO ADMIN !</a>
    </div>

    <a href="admin_product.php">
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
