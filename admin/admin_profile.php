<?php
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
<?php include "admin_frame.php"; ?>


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
