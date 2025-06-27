<?php

$databaseHost = 'localhost';
$databaseName = 'fcsit_kiosk';
$databaseUsername = 'Zombeat123';
$databasePassword = 'Zombeat123';
$charset = 'utf8mb4';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
}
    mysqli_set_charset($conn, $charset);
   //mysqli_close($mysqli);
?>