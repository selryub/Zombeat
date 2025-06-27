<?php
include '../admin/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];

  if ($password !== $confirmPassword) {
    echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
    exit;
  }

  // Enforce password rules
  if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[^\s]{6,8}$/', $password)) {
    echo "<script>alert('Password must be 6-8 characters long, include 1 uppercase letter, 1 number, 1 special character, and no spaces.'); window.history.back();</script>";
    exit;
  }

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO user (full_name, email, password) VALUES (?, ?, ?)");
  if (!$stmt) {
    die("Prepare failed: " . $conn->error);
  }

  $stmt->bind_param("sss", $name, $email, $hashedPassword);

  if ($stmt->execute()) {
    echo "<script>
            alert('Account created successfully!');
            window.location.href = 'login.php';
          </script>";
  } else {
    echo "<script>alert('Error: Email may already exist.'); window.history.back();</script>";
  }

  $stmt->close();
  $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up</title>
  <link rel="stylesheet" href="register.css"/>
</head>
<body>
  <div class="container">
    <div class="form-section">
        <h2>CREATE AN ACCOUNT</h2>
          <div class="signup-box">
            <h3>SIGN UP</h3>

     <!-- Inside your register.php -->
            <form id="signupForm" method="POST" action="register.php">
            <div class="input-group">
              <span><img src="img/profile2.png" class="nameIcon"></span>
              <input type="text" name="name" placeholder="Full Name" required />
            </div>
            <div class="input-group">
              <span><img src="img/email.png" class="emailIcon"></span>
              <input type="email" name="email" placeholder="Email" required />
            </div>
            <div class="input-group">
              <span><img src="img/lock.png" class="lockIcon"></span>
              <input type="password" name="password" id="password" placeholder="Password" required />
            </div>
            <p class="pw-requirement" id="pwRule">
              Password must be 6–8 characters long, include 1 uppercase letter, 1 number, 1 special character, and no spaces.
            </p>
            <div class="input-group">
              <span><img src="img/lock.png" class="lockIcon"></span>
                <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required />
            </div>
            <button type="submit" id="submit-btn">SIGN UP</button>
            </form>
          </div>
          <p>Already Have An Account? <a href="login.php">Login</a></p>
      </div>
    <div class="image-section">
      <img src="img/FIT.jpg" class="kioskImage">
    </div>
  </div>

  <!-- Inline JavaScript from register.js -->
  <script>
      const passwordInput = document.getElementById('password');
  const pwRule = document.getElementById('pwRule');

  passwordInput.addEventListener('focus', () => {
    pwRule.style.display = 'block';
  });

  passwordInput.addEventListener('blur', () => {
    pwRule.style.display = 'none';
  });

    document.getElementById('signupForm').addEventListener('submit', function(e) {
      const password = this.querySelector('input[name="password"]').value;
      const confirmPassword = this.querySelector('input[name="confirmPassword"]').value;

      if (password !== confirmPassword) {
        e.preventDefault();
        alert("Passwords do not match!");
        return;
      }

    });

    // Disable submit by default
    window.addEventListener("DOMContentLoaded", () => {
      submitBtn.disabled = true;
    });
  </script>
</body>
</html>
