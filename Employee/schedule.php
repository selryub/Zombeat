<?php
require "../admin/db_connect.php";


// Handle form submission
if (isset($_POST['submit_schedule'])) {
    $name = $_POST['employee_name'];
    $date = $_POST['work_date'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];

    $stmt = $conn->prepare("INSERT INTO schedule (employee_name, shift_date, shift_time_start, shift_time_end) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $date, $start, $end);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "Shift submitted successfully.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle delete (with FK constraint check)
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $check = $conn->prepare("SELECT * FROM employee WHERE schedule_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "Cannot delete this schedule—it is linked to an employee.";
    } else {
        $conn->query("DELETE FROM schedule WHERE schedule_id = $id");
        $_SESSION['message'] = "Schedule deleted.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<?php include "employee_frame.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Part-time Schedule</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f6ff;
      margin: 0;
      padding: 20px;
    }

    .schedule-container {
      max-width: 800px;
      margin: 40px auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    select, input, button {
      flex: 1;
      min-width: 150px;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    button {
      background: #2c3e50;
      color: white;
      cursor: pointer;
    }

    button:hover {
      background: #1b2838;
    }

    .alert {
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
    }

    .alert-success { background: #d4edda; color: #155724; }
    .alert-error { background: #f8d7da; color: #721c24; }

    table {
      width: 100%;
      border-collapse: collapse;
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
      background-color: #f9f9f9;
    }

    .delete-link {
      color: crimson;
      font-weight: bold;
      text-decoration: none;
    }

    .delete-link:hover {
      text-decoration: underline;
    }

    .go-employee {
      display: inline-block;
      margin-top: 20px;
      background: #2c3e50;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      text-align: center;
    }

    .go-employee:hover {
      background: #1b2838;
    }

    @media (max-width: 768px) {
      form { flex-direction: column; }
      select, input, button { width: 100%; }
    }
  </style>
</head>
<body>

<div class="schedule-container">
  <h2>Available Shift</h2>

  <?php if (!empty($_SESSION['message'])): ?>
    <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form method="POST">
    <select name="employee_name" required>
      <option value="">-- Select Name --</option>
      <option value="Nurul Izzati">Nurul Izzati</option>
      <option value="Epa Haryanee">Epa Haryanee</option>
      <option value="Selma">Selma</option>
      <option value="Eizlyn Ismail">Eizlyn Ismail</option>
      <option value="Nurul Hazwani">Nurul Hazwani</option>
    </select>
    <input type="date" name="work_date" required>
    <input type="time" name="start_time" required>
    <input type="time" name="end_time" required>
    <button type="submit" name="submit_schedule">Check In</button>
  </form>

  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Date</th>
        <th>Start</th>
        <th>End</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $result = $conn->query("SELECT schedule_id, employee_name, shift_date, shift_time_start, shift_time_end FROM schedule ORDER BY shift_date, shift_time_start");
      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
      ?>
        <tr>
          <td><?= htmlspecialchars($row['employee_name']) ?></td>
          <td><?= htmlspecialchars($row['shift_date']) ?></td>
          <td><?= htmlspecialchars($row['shift_time_start']) ?></td>
          <td><?= htmlspecialchars($row['shift_time_end']) ?></td>
          <td><a href="?delete_id=<?= $row['schedule_id'] ?>" class="delete-link" onclick="return confirm('Delete this shift?')">Delete</a></td>
        </tr>
      <?php endwhile; else: ?>
        <tr><td colspan="5" class="no-schedule">No schedule submitted yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <div style="text-align: center;">
    <a href="../admin/admin_employee.php" class="go-employee">Go to Employee</a>
  </div>
</div>

</body>
</html>