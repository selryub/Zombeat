<?php
session_start();
require '../admin/db_connect.php'; // Adjust path if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in user table
    $stmt = $conn->prepare("SELECT user_id FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['reset_email'] = $email;
        $_SESSION['role'] = 'user';
        header("Location: resetPw.php");
        exit;
    }
    $stmt->close();

    // Check admin table
    $stmt = $conn->prepare("SELECT id FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['reset_email'] = $email;
        $_SESSION['role'] = 'admin';
        header("Location: reset_password.php");
        exit;
    }
    $stmt->close();

    echo "<script>alert('Email not found.'); window.history.back();</script>";
}
?>

<!-- HTML Part -->
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="forgotPw.css">
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form method="POST" action="">
            <div class="email-input">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">CONTINUE</button>
            </div>
        </form>
    </div>
</body>
</html>
