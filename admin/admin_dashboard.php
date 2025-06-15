<?php
require "db_connect.php";
include "admin_frame.php";

//Date filters
$period = $_GET['period'] ?? 'daily';
$now = new DateTime();

//Filter
switch ($period) {
  case 'weekly':
    $start = $now->modify('Monday this week')->format('Y-m-d 00:00:00');
    $group = "WEEK(order_date)";
    break;
  case 'monthly':
    $start = (new DateTime('first day of this month'))->format('Y-m-d 00:00:00');
    $group = "MONTH(order_date)";
    break;
  default:
    $start = (new DateTime())->format('Y-m-d 00:00:00');
    $group = "HOUR(order_date)";
}

// Sales chart
$sql = "SELECT DATE(order_date) AS label, SUM(total_amount) AS value
        FROM orders WHERE order_date >= '$start' GROUP BY $group";
$res = $conn->query($sql);
$labels = []; $vals = [];
while ($r = $res->fetch_assoc()) { 
    $labels[] = $r['label']; 
    $vals[] = (float)$r['value']; 
}


// Financial summary
$rev = $conn->query("SELECT SUM(total_amount) AS total FROM orders WHERE order_date >= '$start'")
            ->fetch_assoc()['total'] ?? 0;

//Items sold
$sql = "SELECT SUM(quantity) AS total_sold FROM order_item
        JOIN orders ON order_item.order_id = orders.order_id
        WHERE orders.order_date >= '$start'";
$totalSold = $conn->query($sql)
                  ->fetch_assoc()["total_sold"] ??0;   

//Profit = RM 0.20 per item sold
$profit = $totalSold * 0.20;

// Recent signups
$users = $conn->query("SELECT full_name, registration_date 
                        FROM user 
                        WHERE registration_date >= '$start' 
                        ORDER BY registration_date DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="admindashboard.css">
    <link rel="stylesheet" href="adminstyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>

</head>
<body>
    <div class="content">
    <h2>Dashboard</h2>
    <div class="cards">
        <a href="admin_sales.php" class="card-link">
            <div class="card">Revenue<br><strong>RM <?= number_format($rev,2) ?></strong></div>
        </a>
        <a href="admin_sales.php" class="card-link">
            <div class="card">Items Sold<br><strong><?= $totalSold ?></strong></div>
        </a>
        <a href="admin_financialRecord.php" class="card-link">
            <div class="card">Net Profit<br><strong>RM <?= number_format($profit,2) ?></strong></div>
        </a>
        <a href="admin_employee.php" class="card-link">
            <div class="card">Employees<br><strong><?= $conn->query("SELECT COUNT(*) as cnt FROM employee")->fetch_assoc()['cnt']; ?></strong></div>
        </a>
        <a href="admin_product.php" class="card-link">
            <div class="card">Products<br><strong><?= $conn->query("SELECT COUNT(*) as cnt FROM product")->fetch_assoc()['cnt']; ?></strong></div>
        </a>
        <a href="admin_sales.php" class="card-link">
            <div class="card">Total Orders<br><strong><?= $conn->query("SELECT COUNT(*) as cnt FROM orders")->fetch_assoc()['cnt']; ?></strong></div>
        </a>
    </div>
    <div class="filters">
        <a href="?period=daily"   class="<?= $period=='daily'?'active':'' ?>">Daily</a>
        <a href="?period=weekly"  class="<?= $period=='weekly'?'active':'' ?>">Weekly</a>
        <a href="?period=monthly" class="<?= $period=='monthly'?'active':'' ?>">Monthly</a>
    </div>
    <div class="dashboard-middle">
        <div class="chart-container">
        <canvas id="salesChart" width="800" height="300"></canvas>
        </div>
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>
    </div>

    <h3>Recent Registrations</h3>
    <div class="table-scroll">
        <table class="user-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Registered On</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['full_name']) ?></td>
                <td><?= htmlspecialchars($u['registration_date']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

    <script>
    const labels = <?= json_encode($labels); ?>;
    const data = <?= json_encode($vals); ?>;
    </script>

    <!--Link to JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="admindashboard.js"></script>
    <script src="admin.js"></script>
</body>
</html>

