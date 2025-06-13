<?php
// Include DB connection
include '../admin/db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Prepare statement to avoid SQL injection
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user && password_verify($password, $user['password'])) {
    echo "<script>alert('Login successful!');</script>";
    // Optional: redirect to dashboard
    // header("Location: dashboard.php");
  } else {
    echo "<script>alert('Invalid email or password.');</script>";
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
  <link rel="stylesheet" href="login.css"/>
</head>
<body>
  <div class="container">
    <div class="form-section">
      <h2>WELCOME !</h2>
      <div class="login-box">
        <h3>USER LOGIN</h3>
        <form id="loginForm" method="POST" action="login.php">
          <div class="input-group">
            <span><img src="img/profile2.png" class="emailIcon"></span>
            <input type="text" name="email" placeholder="Email" required />
          </div>
          <div class="input-group">
            <span><img src="img/lock.png" class="lockIcon" /></span>
            <input type="password" name="password" id="password" placeholder="Password" required />
          </div>
          <div class="forgotPassword">
            <input type="checkbox" id="rememberMe" />
            <label for="rememberMe">Remember me</label>
            <label for="forgotPassword">
              <a href="#">Forgot Password?</a>
            </label>
          </div>
          <button type="submit">LOGIN</button>
        </form>
      </div>
      <p>Don't have an account? <a href="register.php">Sign Up</a></p>
    </div>
    <div class="image-section">
      <div class="image-placeholder"></div>
    </div>
  </div>

  <!-- Optional JavaScript validation -->
  <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const email = this.querySelector('input[name="email"]').value;
      const password = this.querySelector('input[name="password"]').value;

      if (!email || !password) {
        e.preventDefault();
        alert("Please fill in all fields.");
      }
    });
  </script>
</body>
</html>
