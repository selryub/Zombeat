<?php
$password = 'emp123';
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo $hashed;
?>
