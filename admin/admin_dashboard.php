<?php
require "db_connect.php";
include "admin_frame.php";

// Get selected period and detailed filters
$period = $_GET['period'] ?? 'daily';
$selectedDate = $_GET['date'] ?? date('Y-m-d');
$selectedMonth = $_GET['month'] ?? date('m');
$selectedYear = $_GET['year'] ?? date('Y');
$selectedWeek = $_GET['week'] ?? 1;

// Define date range based on selected period
switch ($period) {
  case 'weekly':
    $start = new DateTime("$selectedYear-$selectedMonth-01");
    $start->modify("+" . (($selectedWeek - 1) * 7) . " days")->setTime(0, 0);
    $end = clone $start;
    $end->modify('+6 days')->setTime(23, 59, 59);
    break;

  case 'monthly':
    $start = (new DateTime("$selectedYear-$selectedMonth-01"))->setTime(0, 0);
    $end = (new DateTime("$selectedYear-$selectedMonth-01"))->modify('+1 month -1 day')->setTime(23, 59, 59);
    break;

  case 'yearly':
    $start = (new DateTime("$selectedYear-01-01"))->setTime(0, 0);
    $end = (new DateTime("$selectedYear-12-31"))->setTime(23, 59, 59);
    break;

  default:
    $start = (new DateTime($selectedDate))->setTime(0, 0);
    $end = (new DateTime($selectedDate))->setTime(23, 59, 59);
}

$startFormatted = $start->format('Y-m-d H:i:s');
$endFormatted = $end->format('Y-m-d H:i:s');

// Query total items and revenue
$stmt = $conn->prepare("SELECT SUM(oi.quantity) AS items_sold, SUM(o.total_amount) AS revenue FROM order_item oi JOIN orders o ON oi.order_id = o.order_id WHERE o.order_date BETWEEN ? AND ?");
$stmt->bind_param("ss", $startFormatted, $endFormatted);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

$items_sold = $data['items_sold'] ?: 0;
$revenue = $data['revenue'] ?: 0;
$profit = $items_sold * 0.20;

// History query for graph
if ($period === 'monthly') {
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
} elseif ($period === 'weekly') {
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
} elseif ($period === 'yearly') {
  $query = "
    SELECT 
      MONTHNAME(o.order_date) AS label,
      SUM(oi.quantity) AS items_sold,
      SUM(o.total_amount) AS revenue
    FROM order_item oi
    JOIN orders o ON oi.order_id = o.order_id
    WHERE o.order_date BETWEEN ? AND ?
    GROUP BY label
    ORDER BY FIELD(label, 'January','February','March','April','May','June','July','August','September','October','November','December')
  ";
  $hist_stmt = $conn->prepare($query);
  $hist_stmt->bind_param("ss", $startFormatted, $endFormatted);
} else {
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

$hist_stmt->execute();
$raw_data = $hist_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$hist_data = [];
if ($period === 'daily') {
  $hours = range(8, 17);
  foreach ($hours as $h) {
    $label = sprintf("%02d:00", $h);
    $hist_data[$label] = ['items_sold' => 0, 'revenue' => 0];
  }
  foreach ($raw_data as $row) {
    $hour = (new DateTime($row['label']))->format('H:00');
    if (isset($hist_data[$hour])) {
      $hist_data[$hour]['items_sold'] += $row['items_sold'];
      $hist_data[$hour]['revenue'] += $row['revenue'];
    }
  }
} elseif ($period === 'weekly') {
  $weekdays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
  foreach ($weekdays as $day) {
    $hist_data[$day] = ['items_sold' => 0, 'revenue' => 0];
  }
  foreach ($raw_data as $row) {
    $day = $row['label'];
    $hist_data[$day]['items_sold'] += $row['items_sold'];
    $hist_data[$day]['revenue'] += $row['revenue'];
  }
} elseif ($period === 'yearly') {
  $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  foreach ($months as $month) {
    $hist_data[$month] = ['items_sold' => 0, 'revenue' => 0];
  }
  foreach ($raw_data as $row) {
    $month = $row['label'];
    $hist_data[$month]['items_sold'] += $row['items_sold'];
    $hist_data[$month]['revenue'] += $row['revenue'];
  }
} else {
  $hist_data = [
    'Week 1' => ['items_sold' => 0, 'revenue' => 0],
    'Week 2' => ['items_sold' => 0, 'revenue' => 0],
    'Week 3' => ['items_sold' => 0, 'revenue' => 0],
    'Week 4' => ['items_sold' => 0, 'revenue' => 0]
  ];
  foreach ($raw_data as $row) {
    $weekNum = (int)$row['label'];
    $week = 'Week ' . $weekNum;
    if (isset($hist_data[$week])) {
      $hist_data[$week]['items_sold'] += $row['items_sold'];
      $hist_data[$week]['revenue'] += $row['revenue'];
    }
  }
}

//Prepare data array for chart display in JavaScript
$hist_data_array = [];
foreach ($hist_data as $label => $row) {
  $hist_data_array[] = [
    'label' => $label,
    'items_sold' => $row['items_sold'],
    'revenue' => $row['revenue']
  ];
}


// Get most recent user registration within date range
$stmt = $conn->prepare("SELECT full_name, registration_date FROM user WHERE registration_date >= ? ORDER BY registration_date DESC LIMIT 5");
$stmt->bind_param("s", $startFormatted);
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

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
    <title>Admin - Dashboard</title>
    <link rel="stylesheet" href="admindashboard.css">
    <link rel="stylesheet" href="adminstyle.css">
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
        </div>

        <form method="GET" class="filters" id="filterForm">
            <select name="period" id="period" onchange="updateFilterInputs()">
                <option value="daily" <?= $period == 'daily' ? 'selected' : '' ?>>Daily</option>
                <option value="weekly" <?= $period == 'weekly' ? 'selected' : '' ?>>Weekly</option>
                <option value="monthly" <?= $period == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                <option value="yearly" <?= $period == 'yearly' ? 'selected' : '' ?>>Yearly</option>
            </select>

            <!-- Daily Date Picker -->
            <input type="date" name="date" id="dateInput" style="display:none" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">

            <!-- Weekly: Week, Month, Year -->
            <select name="week" id="weekInput" style="display:none">
                <option value="1" <?= isset($_GET['week']) && $_GET['week'] == 1 ? 'selected' : '' ?>>Week 1</option>
                <option value="2" <?= isset($_GET['week']) && $_GET['week'] == 2 ? 'selected' : '' ?>>Week 2</option>
                <option value="3" <?= isset($_GET['week']) && $_GET['week'] == 3 ? 'selected' : '' ?>>Week 3</option>
                <option value="4" <?= isset($_GET['week']) && $_GET['week'] == 4 ? 'selected' : '' ?>>Week 4</option>
            </select>

            <!-- Monthly and Weekly month/year -->
            <select name="month" id="monthInput" style="display:none">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>" <?= (isset($_GET['month']) && $_GET['month'] == $m) ? 'selected' : '' ?>>
                    <?= DateTime::createFromFormat('!m', $m)->format('F') ?>
                </option>
                <?php endfor; ?>
            </select>

            <select name="year" id="yearInput" style="display:none">
                <?php for ($y = date('Y'); $y >= 2022; $y--): ?>
                <option value="<?= $y ?>" <?= (isset($_GET['year']) && $_GET['year'] == $y) ? 'selected' : '' ?>>
                    <?= $y ?>
                </option>
                <?php endfor; ?>
            </select>

            <button type="submit">Apply</button>
        </form>

        <div class="dashboard-middle">
            <div class="left-panel">
                <div class="chart-container">
                <canvas id="salesChart" width="1000" height="500"></canvas>
                </div>
            </div>
            <div class="right-panel">
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
                <div class="popular-container">
                  <h3>Top 5 Popular Products</h3>
                  <div class="table-scroll">
                    <ul class="popular-list">
                      <?php while ($p = $popularRes->fetch_assoc()): ?>
                        <li><?= htmlspecialchars($p["product_name"]) ?> - <?= $p["qty_sold"] ?></li>
                      <?php endwhile; ?>
                    </ul>
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
        <script>
        function updateFilterInputs() {
        const period = document.getElementById('period').value;
        document.getElementById('dateInput').style.display = period === 'daily' ? 'inline-block' : 'none';
        document.getElementById('weekInput').style.display = period === 'weekly' ? 'inline-block' : 'none';
        document.getElementById('monthInput').style.display = (period === 'monthly' || period === 'weekly') ? 'inline-block' : 'none';
        document.getElementById('yearInput').style.display = (period !== 'daily') ? 'inline-block' : 'none';
        }
        updateFilterInputs(); // Call on page load
        </script>
        <script src="admindashboard.js"></script>
        <script src="admin.js"></script>
    </div>
</body>
</html>