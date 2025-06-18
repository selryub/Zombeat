<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$redirect = $_GET['redirect'] ?? 'index.php';

require '../admin/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check user table
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $user = $userResult->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['admin_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = 'user';

        // ✅ Handle "Remember Me"
        if (isset($_POST['rememberMe'])) {
            setcookie("remember_email", $email, time() + (86400 * 30), "/"); // Store for 30 days
        } else {
            setcookie("remember_email", "", time() - 3600, "/"); // Clear cookie
        }

        echo "<script>
                alert('User login successful!');
                window.location.href = '$redirect';
              </script>";
        exit;
    }

    // Check admin table
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $adminResult = $stmt->get_result();
    $admin = $adminResult->fetch_assoc();
    $stmt->close();
    $conn->close();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['email'] = $admin['email'];
        $_SESSION['role'] = 'admin';

        // ✅ Handle "Remember Me"
        if (isset($_POST['rememberMe'])) {
            setcookie("remember_email", $email, time() + (86400 * 30), "/");
        } else {
            setcookie("remember_email", "", time() - 3600, "/");
        }

        echo "<script>
                alert('Admin login successful!');
                window.location.href = '../admin/admin_dashboard.php';
              </script>";
        exit;
    }

    // If both fail
    echo "<script>
            alert('Invalid email or password.');
            window.history.back();
          </script>";
    exit;
}
?>


<!-- HTML START -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign In</title>
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
    <input type="email" name="email" placeholder="Email" value="<?php echo isset($_COOKIE['remember_email']) ? $_COOKIE['remember_email'] : ''; ?>" required />
  </div>
  <div class="input-group">
    <span><img src="img/lock.png" class="lockIcon" /></span>
    <input type="password" name="password" id="password" placeholder="Password" required />
  </div>
  <div class="forgotPassword">
    <input type="checkbox" id="rememberMe" name="rememberMe" <?php if (isset($_COOKIE['remember_email'])) echo 'checked'; ?> />
    <label for="rememberMe">Remember me</label>
    <label for="forgotPassword">
      <a href="forgotPw.php">Forgot Password?</a>
    </label>
  </div>
  <button type="submit">LOGIN</button>
</form>
      </div>
      <p>Don't have an account? <a href="register.php">Sign Up</a></p>
    </div>
    <div class="image-section">
      <img src="img/kiosk2.JPG" class="logoImage">
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
