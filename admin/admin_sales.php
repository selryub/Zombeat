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
        $group = "MONTH(order_date)";
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
$chartDates = $chartRevenue = [];
while ($r = $chartRes->fetch_assoc()) {
    $chartDates[] = $r["date"];
    $chartRevenue[] = $r["revenue"];
}

//Fetch purchase history
$sql = "SELECT o.order_id, o.order_date, o.total_amount, u.full_name
        FROM orders o
        JOIN user u ON o.user_id = u.user_id
        WHERE o.order_date >= '$start'
        ORDER BY o.order_date DESC";

$history = $conn->query($sql);

//Fetch popular products
$sql = "SELECT p.product_name, SUM(oi.quantity) AS qty_sold
        FROM order_item oi
        JOIN product p ON oi.product_id = p.product_id
        JOIN orders o ON oi.order_id = o.order_id
        WHERE o.order_date >= '$start'
        GROUP BY oi.product_id ORDER BY qty_sold DESC LIMIT 5";
$popularRes = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="adminstyle.css">
    <link rel="stylesheet" href="sales.css">
    <script src ="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include "admin_frame.php"; ?>
    <div class="content">
        <h2>Sales Overview</h2>
        <div class="filters">
            <a href="?period=daily" class="<?= $period=="daily"?"active":"" ?>">Daily</a>
            <a href="?period=weekly" class="<?= $period=="weekly"?"active":"" ?>">Weekly</a>
            <a href="?period=monthly" class="<?= $period=="monthly"?"active":"" ?>">Monthly</a>
        </div>
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
        <h3>Purchase History</h3>
        <table class="history">
            <thead><tr><th>Order #</th><th>Date</th><th>Customer</th><th>Amount (RM)</th></tr></thead>
            <tbody>
                <?php while ($row = $history->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["order_id"]) ?></td>
                        <td><?= htmlspecialchars($row["order_date"]) ?></td>
                        <td><?= htmlspecialchars($row["full_name"]) ?></td>
                        <td><?= htmlspecialchars($row["total_amount"], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <h3>Top 5 Popular Products</h3>
        <ul class="popular">
            <?php while ($p = $popularRes->fetch_assoc()): ?>
                <li><?= htmlspecialchars($p["product_name"]) ?> - <?= $p["qty_sold"] ?></li>
            <?php endwhile; ?>
        </ul>
    </div>
    <script>
        const chartDates = <?php echo json_encode($chartDates); ?>;
        const chartRevenue = <?php echo json_encode($chartRevenue) ?>;
    </script>
    <!--Link to JavaScript-->
    <script src="admin.js"></script>
    <script src="sales.js"></script>
</body>
</html>