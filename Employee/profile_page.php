<?php
require "db_connect.php";
require "../admin/db_connect.php";

// Fetch user info
$sql = "SELECT full_name, email FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $user = ["full_name" => "Unknown", "email" => "unknown@example.com"];
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];

    $sql = "UPDATE user SET full_name = ?, email = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $email, $user_id);
    $stmt->execute();

    $_SESSION["username"] = $name;
    $_SESSION["email"] = $email;

    header("Location: profile.php?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="employeestyle.css">
    <style>
      .admin-profile-section {
          padding: 30px;
          font-family: Verdana, Geneva, Tahoma, sans-serif;
          
      }
      .admin-profile-box {
          background: #fff;
          padding: 20px;
          border-radius: 8px;
          max-width: 500px;
          margin: auto;
          box-shadow: 0 0 10px rgba(0,0,0,0.1);
      }
      .admin-profile-box h2 {
          text-align: center;
          margin-bottom: 20px;
      }
      .admin-profile-box p {
            font-family: sans-serif;
            font-size: 16px;
            margin: 30px 0;
      }
      .admin-profile-box form input {
          width: 100%;
          padding: 10px;
          margin: 8px 0;
          box-sizing: border-box;
      }
      .admin-profile-box button {
          padding: 8px 15px;
          margin-top: 10px;
          cursor: pointer;
          border-radius: 10px;
      }
      .alert.success {
          background: #d4edda;
          color: #155724;
          padding: 10px;
          margin: 10px auto;
          border: 1px solid #c3e6cb;
          border-radius: 5px;
          max-width: 500px;
      }
    </style>
</head>
<body>
<?php include "employee_frame.php"; ?>

<!-- Success Message -->
<?php if (isset($_GET['success'])): ?>
    <div class="alert success">Profile updated successfully!</div>
<?php endif; ?>

<!--Employee Profile -->
<section class="employee-profile-section">
    <div class="employee-profile-box">
<!--Admin Profile -->
<section class="admin-profile-section">
    <div class="admin-profile-box">
        <h2>User Profile</h2>
        <p><strong>Name:</strong> <span id="display-name"><?= htmlspecialchars($user["full_name"]) ?></span></p>
        <p><strong>Email:</strong> <span id="display-email"><?= htmlspecialchars($user["email"]) ?></span></p>
        <button id="edit-btn">Edit Profile</button>

        <!-- Edit Form -->
        <form id="edit-form" action="profile.php" method="POST" style="display:none;">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user["full_name"]) ?>" required><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>" required><br>

            <button type="submit">Save</button>
            <button type="button" id="cancel-btn">Cancel</button>
        </form>
    </div>
</section>

<script>
// Show/hide form
document.getElementById("edit-btn").addEventListener("click", function () {
    document.getElementById("edit-form").style.display = "block";
    this.style.display = "none";
});

document.getElementById("cancel-btn").addEventListener("click", function () {
    document.getElementById("edit-form").style.display = "none";
    document.getElementById("edit-btn").style.display = "inline-block";
});
</script>

<script src="employee.js"></script>
</body>
</html>