<?php
session_start();

if (isset($_SESSION["username"]) && $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="eng">
    <head>
        me
    </head>
</html>