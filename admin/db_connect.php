<?php

$databaseHost = 'localhost';
$databaseName = 'u965875395_fcsit_kiosk';
$databaseUsername = 'u965875395_Zombeat123';
$databasePassword = 'Zombeat123';
$charset = 'utf8mb4';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
}
    mysqli_set_charset($conn, $charset);
   //mysqli_close($mysqli);
?>