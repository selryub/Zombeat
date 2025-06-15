<?php
require 'db_connect.php';
require 'fpdf/fpdf.php';

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

$stmt = $conn->prepare("SELECT SUM(oi.quantity) AS items_sold, SUM(o.total_amount) AS revenue FROM order_item oi JOIN orders o ON oi.order_id = o.order_id WHERE o.order_date >= ?");
$stmt->bind_param("s", $start);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$items_sold = $data['items_sold'] ?: 0;
$revenue = $data['revenue'] ?: 0;
$profit = $items_sold * 0.20;

$hist_stmt = $conn->prepare("SELECT DATE(o.order_date) AS date, SUM(oi.quantity) AS items_sold, SUM(o.total_amount) AS revenue FROM order_item oi JOIN orders o ON oi.order_id = o.order_id WHERE o.order_date >= ? GROUP BY DATE(o.order_date)");
$hist_stmt->bind_param("s", $start);
$hist_stmt->execute();
$hist_data = $hist_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

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
  foreach ($hist_data as $r) {
    $date_profit = $r['items_sold'] * 0.20;
    $pdf->Cell(40, 10, $r['date'], 1);
    $pdf->Cell(40, 10, $r['items_sold'], 1);
    $pdf->Cell(50, 10, number_format($r['revenue'], 2), 1);
    $pdf->Cell(50, 10, number_format($date_profit, 2), 1);
    $pdf->Ln();
  }

  $pdf->Output('D', 'financial_report_' . $period . '.pdf');
  exit;
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
        <?php foreach ($hist_data as $r): $date_profit = $r['items_sold'] * 0.20; ?>
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
<a href="?period=<?= $period ?>&download=pdf" class="print-btn">📄 Download PDF</a>
<script>
  const chartData = <?= json_encode($hist_data) ?>;
</script>    
<!--Link to JavaScript-->
    <script src="admin.js"></script>
    <script src="financial.js"></script>
</body>
</html>
