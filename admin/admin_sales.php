<?php
require "db_connect.php";

$period = $_GET["period"] ?? "daily";
$now = new DateTime();

switch ($period) {
    case "weekly":
        $start = $now->modify("Monday this week")->format("Y-m-d 00:00:00");
        $group = "WEEK(order_date)";
        break;
    case "monthly":
        $start = $now->modify("first day of this month")->format("Y-m-d 00:00:00");
        $group = "MONTH(order_status)";
        break;
    default:
    $start = (new DateTime())->format("Y-m-d 00:00:00");
    $group = "HOUR(order_date)";
}

$sql = "SELECT DATE(order_date) AS date, 
        SUM(total_amount) AS revenue
        FROM orders
        WHERE order_date >= '$start'
        GROUP BY $group ORDER BY date ASC";
$chartRes = $conn->query($sql);
$chartDates =)
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


