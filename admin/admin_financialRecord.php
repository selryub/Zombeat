<?php
require 'db_connect.php';
require 'fpdf/fpdf.php';

$period = $_GET['period'] ?? 'daily';
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

$stmt = $conn->prepare("SELECT SUM(oi.quantity) AS items_sold, SUM(o.total_amount) AS revenue FROM order_item oi JOIN orders o ON oi.order_id = o.order_id WHERE o.order_date BETWEEN ? AND ?");
$stmt->bind_param("ss", $startFormatted, $endFormatted);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$items_sold = $data['items_sold'] ?: 0;
$revenue = $data['revenue'] ?: 0;
$profit = $items_sold * 0.20;

if ($period == 'monthly') {
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
} elseif ($period == 'weekly') {
  $query = "
    SELECT 
      DAYNAME(o.order_date) AS label,
      SUM(oi.quantity) AS items_sold,
      SUM(o.total_amount) AS revenue
    FROM order_item oi
    JOIN orders o ON oi.order_id = o.order_id
    WHERE o.order_date BETWEEN ? AND ?
    GROUP BY label
    ORDER BY FIELD(label, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')
  ";
  $hist_stmt = $conn->prepare($query);
  $hist_stmt->bind_param("ss", $startFormatted, $endFormatted);
}
else {
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
if ($period == 'daily') {
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
} elseif ($period == 'weekly') {
    $weekdays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    foreach ($weekdays as $day) {
        $hist_data[$day] = ['items_sold' => 0, 'revenue' => 0];
    }
    foreach ($raw_data as $row) {
        $day = (new DateTime($row['label']))->format('l');
        $hist_data[$day]['items_sold'] += $row['items_sold'];
        $hist_data[$day]['revenue'] += $row['revenue'];
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
          $hist_data[$week]['revenue'] += $row['revenue']; // Previously you did 'items_sold' here again — fixed!
      }
    }
}

if (isset($_GET['download']) && $_GET['download'] === 'pdf') {
  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial', 'B', 16);
  $pdf->Cell(0, 10, 'Financial Report (' . ucfirst($period) . ')', 0, 1, 'C');
  $pdf->Ln(10);

  $pdf->SetFont('Arial', '', 12);
  $pdf->Cell(50, 10, 'Items Sold:', 0, 0);
  $pdf->Cell(40, 10, $items_sold, 0, 1);
  $pdf->Cell(50, 10, 'Revenue (RM):', 0, 0);
  $pdf->Cell(40, 10, number_format($revenue, 2), 0, 1);
  $pdf->Cell(50, 10, 'Net Profit (RM):', 0, 0);
  $pdf->Cell(40, 10, number_format($profit, 2), 0, 1);
  $pdf->Ln(10);

  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(40, 10, 'Date', 1);
  $pdf->Cell(40, 10, 'Items Sold', 1);
  $pdf->Cell(50, 10, 'Revenue (RM)', 1);
  $pdf->Cell(50, 10, 'Profit (RM)', 1);
  $pdf->Ln();

  $pdf->SetFont('Arial', '', 12);
  foreach ($hist_data as $label => $r) {
    $date_profit = $r['items_sold'] * 0.20;
    $pdf->Cell(40, 10, $label, 1);
    $pdf->Cell(40, 10, $r['items_sold'], 1);
    $pdf->Cell(50, 10, number_format($r['revenue'], 2), 1);
    $pdf->Cell(50, 10, number_format($date_profit, 2), 1);
    $pdf->Ln();
  }

  $pdf->Output('D', 'financial_report_' . $period . '.pdf');
  exit;
}

$hist_data_array = [];
foreach ($hist_data as $label => $row) {
  $hist_data_array[] = [
    'label' => $label,
    'items_sold' => $row['items_sold'],
    'revenue' => $row['revenue']
  ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Financial Report</title>
  <link rel="stylesheet" href="financial.css">
  <link rel="stylesheet" href="adminstyle.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
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
    <a href="?period=daily" <?= $period=='daily' ? 'class="active"':'' ?>>Daily</a>
    <a href="?period=weekly" <?= $period=='weekly'? 'class="active"':'' ?>>Weekly</a>
    <a href="?period=monthly" <?= $period=='monthly'? 'class="active"':'' ?>>Monthly</a>
  </div>
  <div class="charts">
  <canvas id="financialChart" width="800" height="400"></canvas>
  </div>
  <div class="table-scroll">
    <table class="report-table">
      <thead>
        <tr><th>Date</th><th>Items Sold</th><th>Revenue (RM)</th><th>Profit (RM)</th></tr>
      </thead>
      <tbody>
        <?php foreach ($hist_data as $label => $r): $date_profit = $r['items_sold'] * 0.20; ?>
        <tr>
          <td><?= htmlspecialchars($label) ?></td>
          <td><?= $r['items_sold'] ?></td>
          <td><?= number_format($r['revenue'],2) ?></td>
          <td><?= number_format($date_profit,2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<a href="?period=<?= $period ?>&download=pdf" class="print-btn">📄 Download PDF</a>
<script>
  const chartData = <?= json_encode($hist_data_array) ?>;
  const period = '<?= $period ?>'; 
  window.chartData = chartData;
  window.period = period;
</script>    
<!--Link to JavaScript-->
    <script src="admin.js"></script>
    <script src="financial.js"></script>
</body>
</html>
