<?php
require "db_connect.php";

$period = $_GET["period"] ?? "daily";
$now = new DateTime();

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

// Summary Cards
$stmt = $conn->prepare("
  SELECT 
    SUM(oi.quantity) AS items_sold, 
    SUM(o.total_amount) AS revenue,
    COUNT(DISTINCT o.order_id) AS total_orders
  FROM order_item oi 
  JOIN orders o ON oi.order_id = o.order_id 
  WHERE o.order_date BETWEEN ? AND ?");
$stmt->bind_param("ss", $startFormatted, $endFormatted);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$items_sold = $data['items_sold'] ?: 0;
$revenue = $data['revenue'] ?: 0;
$total_orders = $data['total_orders'] ?: 0;
$profit = $items_sold * 0.20;

// Historical Chart Query
if ($period == 'monthly') {
  $query = "
    SELECT 
      FLOOR((DAY(o.order_date) - 1) / 7) + 1 AS label,
      SUM(o.total_amount) AS revenue,
      COUNT(DISTINCT o.order_id) AS total_orders
    FROM orders o
    WHERE o.order_date BETWEEN ? AND ?
    GROUP BY label
    ORDER BY label
  ";
  $hist_stmt = $conn->prepare($query);
  $hist_stmt->bind_param("ss", $startFormatted, $endFormatted);
} elseif ($period == 'weekly') {
  $query = "
    SELECT 
      DAYNAME(o.order_date) AS label,
      SUM(o.total_amount) AS revenue,
      COUNT(DISTINCT o.order_id) AS total_orders
    FROM orders o
    WHERE o.order_date BETWEEN ? AND ?
    GROUP BY label
    ORDER BY FIELD(label, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
  ";
  $hist_stmt = $conn->prepare($query);
  $hist_stmt->bind_param("ss", $startFormatted, $endFormatted);
} else {
  $query = "
    SELECT 
      DATE_FORMAT(o.order_date, ?) AS label,
      SUM(o.total_amount) AS revenue,
      COUNT(DISTINCT o.order_id) AS total_orders
    FROM orders o
    WHERE o.order_date BETWEEN ? AND ?
    GROUP BY label
    ORDER BY label
  ";
  $dateFormat = '%Y-%m-%d %H:00:00';
  $hist_stmt = $conn->prepare($query);
  $hist_stmt->bind_param("sss", $dateFormat, $startFormatted, $endFormatted);
}

$hist_stmt->execute();
$raw_data = $hist_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$hist_data = [];

if ($period == 'daily') {
  $hours = range(8, 17);
  foreach ($hours as $h) {
    $label = sprintf("%02d:00", $h);
    $hist_data[$label] = ['revenue' => 0, 'orders' => 0];
  }
  foreach ($raw_data as $row) {
    $hour = (new DateTime($row['label']))->format('H:00');
    if (isset($hist_data[$hour])) {
      $hist_data[$hour]['revenue'] += $row['revenue'];
      $hist_data[$hour]['orders'] += $row['total_orders'];
    }
  }
} elseif ($period == 'weekly') {
  $weekdays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
  foreach ($weekdays as $day) {
    $hist_data[$day] = ['revenue' => 0, 'orders' => 0];
  }
  foreach ($raw_data as $row) {
    $day = $row['label'];
    $hist_data[$day]['revenue'] += $row['revenue'];
    $hist_data[$day]['orders'] += $row['total_orders'];
  }
} else {
  $hist_data = [
    'Week 1' => ['revenue' => 0, 'orders' => 0],
    'Week 2' => ['revenue' => 0, 'orders' => 0],
    'Week 3' => ['revenue' => 0, 'orders' => 0],
    'Week 4' => ['revenue' => 0, 'orders' => 0]
  ];
  foreach ($raw_data as $row) {
    $weekNum = (int)$row['label'];
    $week = 'Week ' . $weekNum;
    if (isset($hist_data[$week])) {
      $hist_data[$week]['revenue'] += $row['revenue'];
      $hist_data[$week]['orders'] += $row['total_orders'];
    }
  }
}

// Prepare for chart
$hist_data_array = [];
foreach ($hist_data as $label => $row) {
  $hist_data_array[] = [
    'label' => $label,
    'revenue' => $row['revenue'],
    'orders' => $row['orders']
  ];
}

$sql = "SELECT o.order_id, o.order_date, o.total_amount, u.full_name
        FROM orders o
        JOIN user u ON o.user_id = u.user_id
        WHERE o.order_date >= '$startFormatted'
        ORDER BY o.order_date DESC";
$history = $conn->query($sql);

//Fetch popular products
$sql = "SELECT p.product_name, SUM(oi.quantity) AS qty_sold
        FROM order_item oi
        JOIN product p ON oi.product_id = p.product_id
        JOIN orders o ON oi.order_id = o.order_id
        WHERE o.order_date >= '$startFormatted'
        GROUP BY oi.product_id ORDER BY qty_sold DESC LIMIT 5";
$popularRes = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="employeestyle.css">
  <link rel="stylesheet" href="sales.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
</head>
<body>
<?php include "employee_frame.php"; ?>
<div class="content">
  <h2>Sales Overview</h2>
  <div class="filters">
    <a href="?period=daily" class="<?= $period=="daily"?"active":"" ?>">Daily</a>
    <a href="?period=weekly" class="<?= $period=="weekly"?"active":"" ?>">Weekly</a>
    <a href="?period=monthly" class="<?= $period=="monthly"?"active":"" ?>">Monthly</a>
  </div>
  <div class="chart-container">
    <canvas id="salesChart" width="400" height="200"></canvas>
  </div>

  <h3>Purchase History</h3>
  <table class="history">
    <thead>
    <tr><th>Order #</th><th>Date</th><th>Customer</th><th>Amount (RM)</th></tr>
    </thead>
    <tbody>
    <?php while ($row = $history->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row["order_id"]) ?></td>
        <td><?= htmlspecialchars($row["order_date"]) ?></td>
        <td><?= htmlspecialchars($row["full_name"]) ?></td>
        <td><?= number_format($row["total_amount"], 2) ?></td>
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
  const chartData = <?= json_encode($hist_data_array) ?>;
  const period = '<?= $period ?>';
  window.chartData = chartData;
  window.period = period;
</script>

<script src="employee.js"></script>
<script src="sales.js"></script>
</body>
</html>