<?php
require_once 'admin/db_connect.php';

header('Content-Type: application/json');

$sql = "SELECT name FROM delivery_locations";
$result = $conn->query($sql);

$locations = [];
while ($row = $result->fetch_assoc()) {
    $locations[] = $row['name'];
}

echo json_encode($locations);
?>
