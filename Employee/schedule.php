<?php
// This can be replaced with include('../includes/db.php'); if you're using a database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Schedule</title>
    <link rel="stylesheet" href="employeestyle.css"> 
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f6ff;
            margin: 0;
            padding: 20px;
        }

        .navbar {
            background: #fff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .schedule-container {
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        select {
            padding: 8px 14px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .no-schedule {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #888;
        }
    </style>
</head>
<body>

<?php include "employee_frame.php"; ?> 

<div class="schedule-container">
    <h2>Employee Work Schedule</h2>

    <!-- Employee Selection Dropdown -->
    <form method="GET" action="">
        <label for="employee">Employee's name:</label>
        <select id="employee" name="employee" onchange="this.form.submit()">
            <option value="">-- Choose Employee --</option>
            <option value="eizlyn" <?= ($_GET['employee'] ?? '') == 'eizlyn' ? 'selected' : '' ?>>Eizlyn Ismail</option>
            <option value="epa" <?= ($_GET['employee'] ?? '') == 'epa' ? 'selected' : '' ?>>Epa Haryanee</option>
            <option value="selma" <?= ($_GET['employee'] ?? '') == 'selma' ? 'selected' : '' ?>>Selma</option>
        </select>
    </form>

    <!-- PHP Schedule Data -->
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Day</th>
                <th>Time</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $selected = $_GET['employee'] ?? 'eizlyn';

                // Static schedule data (you can replace this with a DB query later)
                $all_schedules = [
                    'eizlyn' => [
                        ["2025-06-14", "Friday", "08:00 AM - 12:00 PM", "Cashier"],
                        ["2025-06-15", "Saturday", "12:00 PM - 04:00 PM", "Runner"],
                    ],
                    'epa' => [
                        ["2025-06-14", "Friday", "02:00 PM - 06:00 PM", "Cashier"],
                        ["2025-06-16", "Sunday", "10:00 AM - 02:00 PM", "Stock Handler"],
                    ],
                    'selma' => [
                        ["2025-06-14", "Friday", "08:00 AM - 12:00 PM", "Runner"],
                        ["2025-06-17", "Monday", "12:00 PM - 04:00 PM", "Cashier"],
                    ],
                ];

                if (isset($all_schedules[$selected]) && count($all_schedules[$selected]) > 0) {
                    foreach ($all_schedules[$selected] as $row) {
                        echo "<tr>
                                <td>{$row[0]}</td>
                                <td>{$row[1]}</td>
                                <td>{$row[2]}</td>
                                <td>{$row[3]}</td>
                              </tr>";
                    }
                } else {
                    echo '<tr><td colspan="4" class="no-schedule">No schedule found for this employee.</td></tr>';
                }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
