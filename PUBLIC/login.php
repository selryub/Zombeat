<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$redirect = $_GET['redirect'] ?? 'index.php';

require '../admin/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check user, admin, and employee roles
    $roles = ['user' => 'user', 'admin' => 'admin', 'employees' => 'employees'];

    foreach ($roles as $role => $table) {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $account = $result->fetch_assoc();
        $stmt->close();

        if ($account && password_verify($password, $account['password'])) {
            $_SESSION['email'] = $account['email'];
            $_SESSION['role'] = $role;
            $_SESSION[$role . '_id'] = $account[$role . '_id'];

            if (isset($_POST['rememberMe'])) {
                setcookie("remember_email", $email, time() + (86400 * 30), "/");
            } else {
                setcookie("remember_email", "", time() - 3600, "/");
            }

            $redirectPage = $role === 'admin' ? '../admin/admin_dashboard.php'
                          : ($role === 'employees' ? '../employee/employee_frame.php'
                          : $redirect);

            echo "<script>
                    alert('" . ucfirst($role) . " login successful!');
                    window.location.href = '$redirectPage';
                  </script>";
            $conn->close();
            exit;
        }
    }

    $conn->close();

    // If login fails
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
        <h3>LOGIN</h3>
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