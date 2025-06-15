<?php
require 'db_connect.php';

$period = $_GET['period'] ?? 'daily';
$now = new DateTime();
switch ($period) {
  case 'weekly':
    $start = $now->modify('Monday this week')->format('Y-m-d 00:00:00');
    break;
  case 'monthly':
    $start = (new DateTime('first day of this month'))->format('Y-m-d 00:00:00');
    break;
  default:
    $start = (new DateTime())->format('Y-m-d 00:00:00');
}

$stmt = $conn->prepare("
  SELECT SUM(oi.quantity) AS items_sold, 
         SUM(o.total_amount) AS revenue
  FROM order_item oi
  JOIN orders o ON oi.order_id = o.order_id
  WHERE o.order_date >= ?
");
$stmt->bind_param("s", $start);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$items_sold = $data['items_sold'] ?: 0;
$revenue = $data['revenue'] ?: 0;
$profit = $items_sold * 0.20;

$hist_stmt = $conn->prepare("
  SELECT DATE(o.order_date) AS date,
         SUM(oi.quantity) AS items_sold,
         SUM(o.total_amount) AS revenue
  FROM order_item oi
  JOIN orders o ON oi.order_id = o.order_id
  WHERE o.order_date >= ?
  GROUP BY DATE(o.order_date)
");
$hist_stmt->bind_param("s", $start);
$hist_stmt->execute();
$hist_data = $hist_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Profile</title>
    <link rel="stylesheet" href="financial.css">
    <link rel="stylesheet" href="adminstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>
    <?php include "admin_frame.php"; ?>

    <div class="content">
        <h2>Financial Overview</h2>
        <div class="cards">
                <div class="card">Items Sold<br><strong><?= $items_sold ?></strong></div>
    <div class="card">Revenue<br><strong>RM <?= number_format($revenue,2) ?></strong></div>
    <div class="card">Net Profit<br><strong>RM <?= number_format($profit,2) ?></strong></div>
        </div>
    <div class="filters">
        <a href="?period=daily"   <?= $period=='daily' ? 'class="active"':'' ?>>Daily</a>
        <a href="?period=weekly"  <?= $period=='weekly'? 'class="active"':'' ?>>Weekly</a>
        <a href="?period=monthly" <?= $period=='monthly'? 'class="active"':'' ?>>Monthly</a>
    </div>
        <div class="charts">
            <canvas id="revExpChart"></canvas>
        </div>
  <h3>Details by Date</h3>
  <div class="table-scroll">
    <table class="report-table">
      <thead>
        <tr><th>Date</th><th>Items Sold</th><th>Revenue (RM)</th><th>Profit (RM)</th></tr>
      </thead>
      <tbody>
        <?php foreach ($hist_data as $r): 
          $date_profit = $r['items_sold'] * 0.20;
        ?>
        <tr>
          <td><?= htmlspecialchars($r['date']) ?></td>
          <td><?= $r['items_sold'] ?></td>
          <td><?= number_format($r['revenue'],2) ?></td>
          <td><?= number_format($date_profit,2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
    <button onclick="downloadPDF()" class="print-btn">📄 Download PDF</button>
    
    <!--Link to JavaScript-->
    <script src="admin.js"></script>
    <script src="financial.js"></script>
</body>
</html>
