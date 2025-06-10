<?php
session_start();

if (isset($_SESSION["username"]) && $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
<?php include "admin_frame.php"; ?>

    <!--Link to JavaScript-->
    <script src="admin.js"></script>


