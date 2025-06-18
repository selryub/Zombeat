<?php
require "db_connect.php";
include "admin_frame.php";

//Get period parameter
$period = $_GET['period'] ?? 'daily';
$now = new DateTime();

//Determine start & end date based on selected period
switch ($period) {
  case 'weekly':
    $start = $now->modify('Monday this week')->setTime(0, 0);
    $end = (new DateTime('last day of this month'))->setTime(23, 59, 59);
    break;
  case 'monthly':
    $start = (new DateTime('first day of this month'))->setTime(0, 0);
    $end = (new DateTime('last day of this month'))->setTime(23, 59, 59);
    break;
  default:
    $start = (new DateTime())->setTime(0, 0);
    $end = (new DateTime())->setTime(23, 59, 59);
}

$startFormatted = $start->format('Y-m-d H:i:s');
$endFormatted = $end->format('Y-m-d H:i:s');

//Get total items sold & total revenue within selected period
$stmt = $conn->prepare("SELECT SUM(oi.quantity) AS items_sold, SUM(o.total_amount) AS revenue FROM order_item oi JOIN orders o ON oi.order_id = o.order_id WHERE o.order_date BETWEEN ? AND ?");
$stmt->bind_param("ss", $startFormatted, $endFormatted);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$items_sold = $data['items_sold'] ?: 0;
$revenue = $data['revenue'] ?: 0;
$profit = $items_sold * 0.20;  //Profit is RM0.20 per item sold

//Get data history for chart (selected period)
if ($period == 'monthly') {   //Group by week num in the month
  $query = "
    SELECT 
      FLOOR((DAY(o.order_date) - 1) / 7) + 1 AS label,
      SUM(oi.quantity) AS items_sold,
      SUM(o.total_amount) AS revenue
    FROM order_item oi
    JOIN orders o ON oi.order_id = o.order_id
    WHERE o.order_date BETWEEN ? AND ?
    GROUP BY label
    ORDER BY label
  ";
  $hist_stmt = $conn->prepare($query);
  $hist_stmt->bind_param("ss", $startFormatted, $endFormatted);
} elseif ($period == 'weekly') {    //Group by day of the week
  $query = "
    SELECT 
      DAYNAME(o.order_date) AS label,
      SUM(oi.quantity) AS items_sold,
      SUM(o.total_amount) AS revenue
    FROM order_item oi
    JOIN orders o ON oi.order_id = o.order_id
    WHERE o.order_date BETWEEN ? AND ?
    GROUP BY label
    ORDER BY FIELD(label, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
  ";
  $hist_stmt = $conn->prepare($query);
  $hist_stmt->bind_param("ss", $startFormatted, $endFormatted);
} else {      //Group by hour (daily)
  $query = "
    SELECT 
      DATE_FORMAT(o.order_date, ?) AS label,
      SUM(oi.quantity) AS items_sold,
      SUM(o.total_amount) AS revenue
    FROM order_item oi
    JOIN orders o ON oi.order_id = o.order_id
    WHERE o.order_date BETWEEN ? AND ?
    GROUP BY label
    ORDER BY label
  ";
  $dateFormat = '%Y-%m-%d %H:00:00';
  $hist_stmt = $conn->prepare($query);
  $hist_stmt->bind_param("sss", $dateFormat, $startFormatted, $endFormatted);
}

//Execute and get chart data
$hist_stmt->execute();
$raw_data = $hist_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$hist_data = [];

//Format data for Chart.js
if ($period == 'daily') {
    $hours = range(8, 17);    //hourly slots: 8 AM to PM
    foreach ($hours as $h) {
        $label = sprintf("%02d:00", $h);
        $hist_data[$label] = ['revenue' => 0];
    }
    foreach ($raw_data as $row) {
        $hour = (new DateTime($row['label']))->format('H:00');
        if (isset($hist_data[$hour])) {
            $hist_data[$hour]['revenue'] += $row['revenue'];
        }
    }
} elseif ($period == 'weekly') {
    $weekdays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    foreach ($weekdays as $day) {
        $hist_data[$day] = ['revenue' => 0];
    }
    foreach ($raw_data as $row) {
        $day = $row['label'];
        $hist_data[$day]['revenue'] += $row['revenue'];
    }
} else {
    $hist_data = [
        'Week 1' => ['revenue' => 0],
        'Week 2' => ['revenue' => 0],
        'Week 3' => ['revenue' => 0],
        'Week 4' => ['revenue' => 0]
    ];
    foreach ($raw_data as $row) {
      $weekNum = (int)$row['label'];
      $week = 'Week ' . $weekNum;
      if (isset($hist_data[$week])) {
          $hist_data[$week]['revenue'] += $row['revenue'];
      }
    }
}

//Convert data into array format for JavaScript
$hist_data_array = [];
foreach ($hist_data as $label => $row) {
  $hist_data_array[] = [
    'label' => $label,
    'revenue' => $row['revenue']
  ];
}

// Get most recent user registration within date range
$stmt = $conn->prepare("SELECT full_name, registration_date FROM user WHERE registration_date >= ? ORDER BY registration_date DESC LIMIT 5");
$stmt->bind_param("s", $startFormatted);
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin - Dashboard</title>
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
                <div class="card">Revenue<br><strong>RM <?= number_format($revenue, 2) ?></strong></div>
            </a>
            <a href="admin_sales.php" class="card-link">
                <div class="card">Items Sold<br><strong><?= $items_sold ?></strong></div>
            </a>
            <a href="admin_financialRecord.php" class="card-link">
                <div class="card">Net Profit<br><strong>RM <?= number_format($profit, 2) ?></strong></div>
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
            <a href="?period=daily" class="<?= $period == 'daily' ? 'active' : '' ?>">Daily</a>
            <a href="?period=weekly" class="<?= $period == 'weekly' ? 'active' : '' ?>">Weekly</a>
            <a href="?period=monthly" class="<?= $period == 'monthly' ? 'active' : '' ?>">Monthly</a>
        </div>

        <div class="dashboard-middle">
            <div class="left-panel">
                <div class="chart-container">
                <canvas id="salesChart" width="1000" height="500"></canvas>
                </div>
            </div>
            <div class="right-panel">
                <div class="calendar-container">
                    <div id="calendar"></div>
                </div>
                <div class="registration-container">
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
                </div>
                
            </div>
        </div>

        <script>
            const chartData = <?= json_encode($hist_data_array) ?>;
            const period = '<?= $period ?>';
            window.chartData = chartData;
            window.period = period;
        </script>

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="admindashboard.js"></script>
        <script src="admin.js"></script>
    </div>
</body>
</html>