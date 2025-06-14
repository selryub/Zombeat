<?php
// Include DB connection if using MySQL
// include('../includes/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Schedule</title>
    <link rel="stylesheet" href="employeestyle.css"> <!-- Adjust if needed -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f6ff;
            margin: 0;
            padding: 19px;
        }

        .navbar {
            background: #fff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            height: 50px;
        }

        .search-bar {
            flex: 1;
            text-align: center;
        }

        .schedule-container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
    </style>
</head>
<body>

<?php include "admin_frame.php"; ?>

<div class="schedule-container">
    <h2>Employee Work Schedule</h2>
    
    <label for="employee">Select Employee:</label>
    <select id="employee" name="employee">
        <option value="john_doe">Epa</option>
        <option value="jane_smith">Eizlyn Ismail</option>
        <!-- Add dynamic names from DB -->
    </select>

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
                // Fetch schedule from DB
                $schedules = [
                    ["2025-06-14", "Friday", "08:00 AM - 12:00 PM", "Cashier"],
                    ["2025-06-15", "Saturday", "12:00 PM - 04:00 PM", "Kitchen Helper"],
                ];

                foreach ($schedules as $row) {
                    echo "<tr>
                            <td>{$row[0]}</td>
                            <td>{$row[1]}</td>
                            <td>{$row[2]}</td>
                            <td>{$row[3]}</td>
                          </tr>";
                }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>