<?php
session_start();
require "db_connect.php";

if (isset($_SESSION["user_id"]) && $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$name = $_POST["name"];
$email = $_POST["email"];

$sql = "UPDATE user SET full_name = ?, email = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $name, $email, $user_id);
$stmt->execute();

$_SESSION["username"] = $name;
$_SESSION["email"] = $email;

header('Location: admin_profile.php');
exit();

?>