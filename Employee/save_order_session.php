<?php
session_start();

// Get JSON input from request body
$input = json_decode(file_get_contents("php://input"), true);

// Validate request and store data in session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($input)) {
    $_SESSION['order_data'] = $input;
    echo 'success';
} else {
    echo 'invalid-method';
}
?>
