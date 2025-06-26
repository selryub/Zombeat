<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = $_POST['email'];
    $subject = "FCSIT Kiosk - Your Order Receipt";

    $message = $_POST['message']; 
    $headers = "From: fcsiteats@example.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "success";
    } else {
        echo "failed";
    }
} else {
    echo "invalid";
}
?>
