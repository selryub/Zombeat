<?php
require 'db_connect.php';

$sql = "SELECT DATE(order_date) AS date, 
        SUM(total_amount) AS revenue
        FROM orders
        GROUP BY DATE(order_date)";
$res = $conn->query($sql);
$daily = []; $total_rev=0;
while ($r = $res->fetch_assoc()) {
    $daily[] = $r;
    $total_rev += $r["revenue"];
}

// $sql = "SELECT  
//         SUM(amount) AS expense FROM financial_report 
//         GROUP BY DATE(report_date)";
// $res = $conn->query($sql);
// $expense_data = []; $total_exp= 0;
// while ($r = $res->fetch_assoc()) {
//     $expense_data[] = $r;
//     $total_exp += $r["expense"];
// }

$net_profit = $total_rev - $total_exp;
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
</head>
<body>
    <?php include "admin_frame.php"; ?>

    <div class="content">
        <h2>Financial Overview</h2>
        <div class="cards">
            <div class="card">Total Revenue<br><strong>RM <?= number_format($total_rev,2) ?></strong></div>
             <div class="card">Total Expenses<br><strong>RM <?= number_format($total_exp,2) ?></strong></div>
              <div class="card">Net Profit<br><strong>RM <?= number_format($net_profit,2) ?></strong></div>
        </div>

        <div class="charts">
            <canvas id="revExpChart"></canvas>
        </div>

        <div class="tables">
            <h3>Revenue Details</h3>
            <table><thead><tr><th>Date</th><th>Revenue (RM)</th></tr></thead><tbody>
                <?php foreach ($daily as $r): ?>
                    <tr><td><?= htmlspecialchars($r['date']) ?></td><td><?= number_format($r['revenue'],2) ?></td></tr>
                <?php endforeach; ?>
            </tbody></table>

            <h3>Expenses Details</h3>
            <table><thead><tr><th>Date</th><th>Expense (RM)</th></tr></thead><tbody>
                <?php foreach ($expense_data as $r): ?>
                    <tr><td><?= htmlspecialchars($r['date']) ?></td><td><?= number_format($r['expense'],2) ?></td></tr>
                <?php endforeach; ?>
            </tbody></table>
        </div>
    </div>

    <!--Link to JavaScript-->
    <script src="admin.js"></script>
    <script src="financial.js"></script>


