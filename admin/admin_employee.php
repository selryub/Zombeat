<?php
require "db_connect.php";
$sql = "SELECT user.full_name, employee.hourly_rate, employee.attendance_status 
        FROM employee
        INNER JOIN user ON employee.user_id = user.user_id";
$result = $conn->query($sql);

$employees = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin - Employee List</title>
    <link rel="stylesheet" href="employeelist.css">
</head>
<body>
<?php include "admin_frame.php"; ?>

<div class="content">
    <h2>Employee List</h2>
    <table id = "EmployeeTable">
        <thead>
            <tr>
                <th>Name</th><th>Wage (RM/hr)</th><th>Attendance Status</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($employees as $emp): ?>
    <tr>
        <td><?= htmlspecialchars($emp["full_name"]) ?></td>
        <td><?= htmlspecialchars($emp["hourly_rate"]) ?></td>
        <td><?= htmlspecialchars($emp["attendance_status"]) ?></td>
    </tr>
    <?php endforeach; ?>
</tbody>
    </table>
</div>

    <!--Link to JavaScript-->
    <script src="employeelist.js"></script>
    <script src="admin.js"></script>

   

