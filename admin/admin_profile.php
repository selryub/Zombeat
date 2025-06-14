<?php
session_start();
require "db_connect.php";

if (isset($_SESSION["user_id"]) && $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

//Fetch from Database
//$username = $_SESSION["username"];
$sql = "SELECT full_name, email, phone
        FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if(!$user) {
    $user = ["full_name" => "Unknown", "email" => "unknown@exampke.com"];
}
// $user = [
//     "name" => $_SESSION["username"] ?? "epa",
//     "email" => $_SESSION["email"] ?? "admin@unimas.my",
//     "phone" => $_SESSION["phone"] ?? "012-3456789",
//     "role" => $_SESSION["role"] ?? "admin",
// ];

//update profile
//$user_id = $_SESSION["user_id"];
$name = $_POST["name"];
$email = $_POST["email"];

$sql = "UPDATE user SET full_name = ?, email = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $name, $email, $user_id);
$stmt->execute();

$_SESSION["username"] = $name;
$_SESSION["email"] = $email;
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
            <p><strong>Name:</strong> <span id="display-name"><?= htmlspecialchars($user["full_name"]) ?></span></p>
            <p><strong>Email:</strong> <span id="display-email"><?= htmlspecialchars($user["email"]) ?></span></p>
            <p><strong>Phone:</strong> <span id="display-phone"><?= htmlspecialchars($user["phone"]) ?></span></p>
            <button id="edit-btn">Edit Profile</button>

        <!-- Edit Form -->
        <form id="edit-form" action="update_profile.php" method="POST" style="display:none;">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user["full_name"]) ?>" required><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>" required><br>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user["phone"]) ?>" required><br>

            <button type="submit">Save</button>
            <button type="button" id="cancel-btn">Cancel</button>
        </form>
    </div>
</section>

    <!--Link to JavaScript-->
    <script src="profile.js"></script>
    <script src="admin.js"></script>
