
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Profile</title>
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
        <tbody></tbody>
    </table>
</div>

<div class="paginatiom">
    <button id="back-btn">Back</button>
</div>

    <!--Link to JavaScript-->
    <script src="employeelist.js"></script>
    <script src="admin.js"></script>

   

