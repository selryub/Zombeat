<?php
require "db_connect.php";

// Fetch member info
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
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];
    
    $errors = [];
    
    // Validate current password if trying to change password
    if (!empty($new_password)) {
        // Fetch current password hash
        $sql = "SELECT password FROM user WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        
        if (!password_verify($current_password, $user_data['password'])) {
            $errors[] = "Current password is incorrect.";
        }
        
        // Validate new password requirements
        if (strlen($new_password) < 6 || strlen($new_password) > 8) {
            $errors[] = "Password must be 6-8 characters long.";
        }
        
        if (!preg_match('/[A-Z]/', $new_password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        
        if (!preg_match('/[0-9]/', $new_password)) {
            $errors[] = "Password must contain at least one number.";
        }
        
        if (!preg_match('/[^a-zA-Z0-9]/', $new_password)) {
            $errors[] = "Password must contain at least one special character.";
        }
        
        if (preg_match('/\s/', $new_password)) {
            $errors[] = "Password cannot contain spaces.";
        }
        
        if ($new_password !== $confirm_password) {
            $errors[] = "New passwords do not match.";
        }
    }
    
    // Validate full name (alphabets only)
    if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
        $errors[] = "Full name must contain only alphabetic characters.";
    }
    
    // If no errors, update the profile
    if (empty($errors)) {
        if (!empty($new_password)) {
            // Update with new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE user SET full_name = ?, email = ?, password = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $name, $email, $hashed_password, $user_id);
        } else {
            // Update without password change
            $sql = "UPDATE user SET full_name = ?, email = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $name, $email, $user_id);
        }
        
        $stmt->execute();
        
        $_SESSION["username"] = $name;
        $_SESSION["email"] = $email;
        
        header("Location: member_profile.php?success=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Member Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="memberstyle.css">
    <style>
      .member-profile-section {
          padding: 30px;
          font-family: Verdana, Geneva, Tahoma, sans-serif;
          background-color: #f5f5f5;
          min-height: 100vh;
      }
      .member-profile-box {
          background: #fff;
          padding: 30px;
          border-radius: 10px;
          max-width: 600px;
          margin: auto;
          box-shadow: 0 0 15px rgba(0,0,0,0.1);
      }
      .member-profile-box h2 {
          text-align: center;
          margin-bottom: 30px;
          color: #333;
          font-size: 24px;
      }
      .member-profile-box p {
          font-family: sans-serif;
          font-size: 16px;
          margin: 20px 0;
          color: #555;
      }
      .member-profile-box form input {
          width: 100%;
          padding: 12px;
          margin: 8px 0 15px 0;
          box-sizing: border-box;
          border: 1px solid #ddd;
          border-radius: 5px;
          font-size: 14px;
      }
      .member-profile-box label {
          font-weight: bold;
          color: #333;
          display: block;
          margin-bottom: 5px;
      }
      .member-profile-box button {
          padding: 10px 20px;
          margin: 10px 5px 0 0;
          cursor: pointer;
          border-radius: 5px;
          border: none;
          font-size: 14px;
          font-weight: bold;
      }
      .edit-btn {
          background-color: #007bff;
          color: white;
      }
      .edit-btn:hover {
          background-color: #0056b3;
      }
      .save-btn {
          background-color: #28a745;
          color: white;
      }
      .save-btn:hover {
          background-color: #1e7e34;
      }
      .cancel-btn {
          background-color: #6c757d;
          color: white;
      }
      .cancel-btn:hover {
          background-color: #545b62;
      }
      .alert.success {
          background: #d4edda;
          color: #155724;
          padding: 15px;
          margin: 20px auto;
          border: 1px solid #c3e6cb;
          border-radius: 5px;
          max-width: 600px;
          text-align: center;
      }
      .alert.error {
          background: #f8d7da;
          color: #721c24;
          padding: 15px;
          margin: 20px auto;
          border: 1px solid #f5c6cb;
          border-radius: 5px;
          max-width: 600px;
      }
      .password-section {
          border-top: 1px solid #eee;
          margin-top: 20px;
          padding-top: 20px;
      }
      .password-requirements {
          font-size: 12px;
          color: #666;
          margin-top: 5px;
          line-height: 1.4;
      }
      .info-display {
          background-color: #f8f9fa;
          padding: 15px;
          border-radius: 5px;
          margin: 10px 0;
      }
    </style>
</head>
<body>
<?php include "member_frame.php"; ?>

<!-- Success Message -->
<?php if (isset($_GET['success'])): ?>
    <div class="alert success">Profile updated successfully!</div>
<?php endif; ?>

<!-- Error Messages -->
<?php if (!empty($errors)): ?>
    <div class="alert error">
        <ul style="margin: 0; padding-left: 20px;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Member Profile -->
<section class="member-profile-section">
    <div class="member-profile-box">
        <h2>My Profile</h2>
        
        <!-- Display Mode -->
        <div id="display-mode">
            <div class="info-display">
                <p><strong>Full Name:</strong> <span id="display-name"><?= htmlspecialchars($user["full_name"]) ?></span></p>
                <p><strong>Email:</strong> <span id="display-email"><?= htmlspecialchars($user["email"]) ?></span></p>
            </div>
            <button id="edit-btn" class="edit-btn">Edit Profile</button>
        </div>

        <!-- Edit Form -->
        <form id="edit-form" action="member_profile.php" method="POST" style="display:none;">
            <div>
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user["full_name"]) ?>" required>
                <div class="password-requirements">Only alphabetic characters (uppercase, lowercase, or mix) are allowed</div>
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>" required>
            </div>

            <div class="password-section">
                <h3 style="color: #333; margin-bottom: 15px;">Change Password (Optional)</h3>
                
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password">
                
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password">
                <div class="password-requirements">
                    Password requirements:<br>
                    • 6-8 characters long<br>
                    • Must contain ONE uppercase letter<br>
                    • Must contain ONE number<br>
                    • Must contain ONE special character<br>
                    • No spaces allowed
                </div>
                
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>

            <div style="margin-top: 25px;">
                <button type="submit" class="save-btn">Save Changes</button>
                <button type="button" id="cancel-btn" class="cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
</section>

<script>
// Show/hide form
document.getElementById("edit-btn").addEventListener("click", function () {
    document.getElementById("edit-form").style.display = "block";
    document.getElementById("display-mode").style.display = "none";
});

document.getElementById("cancel-btn").addEventListener("click", function () {
    document.getElementById("edit-form").style.display = "none";
    document.getElementById("display-mode").style.display = "block";
    
    // Clear password fields
    document.getElementById("current_password").value = "";
    document.getElementById("new_password").value = "";
    document.getElementById("confirm_password").value = "";
});

// Password validation on input
document.getElementById("new_password").addEventListener("input", function() {
    const password = this.value;
    const currentField = document.getElementById("current_password");
    
    if (password.length > 0) {
        currentField.setAttribute("required", "required");
    } else {
        currentField.removeAttribute("required");
    }
});
</script>

<script src="member.js"></script>
</body>
</html>