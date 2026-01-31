<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['alumni_id'])) exit("Access denied.");
$events = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>View Events</title>
  
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #ffecd2, #fcb69f);
      margin: 0;
      padding: 0;
      color: #3e3e3e;
    }

    .container {
      max-width: 950px;
      margin: 50px auto;
      background: #ffffffcc;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    h2 {
      text-align: center;
      color: #d84315;
      font-size: 2.8em;
      margin-bottom: 30px;
      text-shadow: 1px 1px #ffe0b2;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background: linear-gradient(to right, #ff7043, #ff8a65);
      color: white;
    }

    tr:nth-child(even) {
      background-color: #fff3e0;
    }

    tr:hover {
      background-color: #ffe0b2;
    }

    input[type="submit"], .back-btn {
      background: #ff7043;
      color: white;
      padding: 12px 22px;
      text-decoration: none;
      font-weight: bold;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .back-btn {
      background-color: #6d4c41;
      margin-top: 20px;
      display: inline-block;
    }

    .back-btn:hover, input[type="submit"]:hover {
      background-color: #bf360c;
    }

    a {
      color: #ff7043;
      font-weight: bold;
    }
  </style>


  </style>
</head>
<body>
  <div class="container">
    <h2>Upcoming Events</h2>
    <ul>
    <?php while ($e = $events->fetch_assoc()): ?>
      <li>
        <strong><?= $e['title'] ?></strong><br>
        <?= $e['event_date'] ?> - <?= $e['description'] ?><br>
        <a href="register_event.php?event_id=<?= $e['id'] ?>">Register</a>
      </li>
    <?php endwhile; ?>
    </ul>
    <a href="alumni_dashboard.php" class="back-btn">Back to Dashboard</a>
  </div>
</body>
</html>
