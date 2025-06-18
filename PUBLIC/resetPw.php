<?php
session_start();
require '../admin/db_connect.php'; // Adjust path

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['role'])) {
    header("Location: forgot_password.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit;
    }

    // Validate password strength
    if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*\W)(?!.*\s).{6,8}$/", $newPassword)) {
        echo "<script>alert('Password does not meet the criteria.'); window.history.back();</script>";
        exit;
    }

    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $email = $_SESSION['reset_email'];
    $role = $_SESSION['role'];

    $table = $role === 'admin' ? 'admin' : 'user';

    $stmt = $conn->prepare("UPDATE $table SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed, $email);
    $stmt->execute();
    $stmt->close();

    session_unset();
    session_destroy();

    echo "<script>alert('Password updated successfully.'); window.location.href = 'login.php';</script>";
}
?>

<!-- HTML Part -->
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="resetPw.css">
</head>
<body>
    <div class="container">
        <div class="resetPw-box">
            <h2>RESET PASSWORD</h2>
        </div>
        
        <form method="POST" action="">
            <div class="password-input">
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit">UPDATE</button>
            </div>
        </form>
    </div>
</body>
</html>
