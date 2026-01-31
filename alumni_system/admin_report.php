<?php
include 'db_connect.php';


$total_logins = $conn->query("SELECT COUNT(*) AS total FROM alumni_logins")->fetch_assoc()['total'];
$total_events = $conn->query("SELECT COUNT(*) AS total FROM event_registrations")->fetch_assoc()['total'];
$total_donations = $conn->query("SELECT COUNT(*) AS total FROM donations")->fetch_assoc()['total'];
$total_amount = $conn->query("SELECT SUM(amount) AS total FROM donations")->fetch_assoc()['total'] ?? 0;


$years = $conn->query("SELECT DISTINCT graduation_year FROM alumni ORDER BY graduation_year DESC");
$events = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Reports</title>
    <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #a1ffce, #faffd1);
    margin: 0;
    padding: 30px;
    color: #2e3c61;
  }

  .container {
    max-width: 1000px;
    margin: auto;
    background: #ffffffee;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    color: #00796b;
    font-size: 2.4em;
    margin-bottom: 35px;
  }

  .report-box {
    background: #e0f2f1;
    padding: 25px;
    border-left: 6px solid #009688;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.05);
  }

  label, select {
    display: block;
    margin-top: 10px;
    font-weight: bold;
  }

  select {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #ccc;
  }

  .btn {
    background-color: #00796b;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
  }

  .btn:hover {
    background-color: #004d40;
  }
</style>
</head>
<body>
<div class="container">
<h2>Alumni Activity Report</h2>

<h3>Summary Statistics</h3>
<ul>
    <li>Total Logins: <strong><?= $total_logins ?></strong></li>
    <li>Total Event Registrations: <strong><?= $total_events ?></strong></li>
    <li>Total Donations: <strong><?= $total_donations ?></strong></li>
    <li>Total Donation Amount: <strong>RM <?= number_format($total_amount, 2) ?></strong></li>
</ul>

<h3>Filter Reports</h3>
<form method="POST" action="report_export.php">
    Graduation Year:
    <select name="year">
        <option value="">-- All Years --</option>
        <?php while ($y = $years->fetch_assoc()): ?>
        <option value="<?= $y['graduation_year'] ?>"><?= $y['graduation_year'] ?></option>
        <?php endwhile; ?>
    </select>

    Event:
    <select name="event_id">
        <option value="">-- All Events --</option>
        <?php while ($e = $events->fetch_assoc()): ?>
        <option value="<?= $e['id'] ?>"><?= $e['title'] ?> (<?= $e['event_date'] ?>)</option>
        <?php endwhile; ?>
    </select>

    <br><br>
    <input type="submit" name="export_pdf" value="Export as PDF">
    <input type="submit" name="export_excel" value="Export as Excel">
</form>

<a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
