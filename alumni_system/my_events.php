<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['alumni_id'])) {
    echo "Access denied.";
    exit();
}
$alumni_id = $_SESSION['alumni_id'];
$sql = "SELECT events.title, events.description, events.event_date 
        FROM event_registrations
        JOIN events ON event_registrations.event_id = events.id
        WHERE event_registrations.alumni_id = $alumni_id
        ORDER BY events.event_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Registered Events</title>
    <style>
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #fceabb, #f8b500);
    margin: 0;
    padding: 0;
    color: #444;
  }

  .container {
    max-width: 850px;
    margin: 50px auto;
    background: #fff9e6;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  }

  h2 {
    text-align: center;
    font-size: 2.4em;
    color: #ef6c00;
    margin-bottom: 25px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
  }

  th, td {
    padding: 14px;
    border: 1px solid #ddd;
    text-align: left;
  }

  th {
    background-color: #ef6c00;
    color: white;
  }

  tr:nth-child(odd) {
    background-color: #fff3e0;
  }

  tr:hover {
    background-color: #ffe0b2;
  }

  .back-btn {
    display: inline-block;
    background-color: #ef6c00;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    font-weight: bold;
    border-radius: 8px;
    margin-top: 20px;
    transition: background-color 0.3s ease;
  }

  .back-btn:hover {
    background-color: #e65100;
  }
</style>

</head>
<body>
<div class="container">
    <h2>My Registered Events</h2>
    <?php
    if ($result->num_rows > 0) {
        echo "<table><tr><th>Title</th><th>Date</th><th>Description</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['title']}</td>
                    <td>{$row['event_date']}</td>
                    <td>{$row['description']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "You have not registered for any events yet.";
    }
    ?>
    <a href="alumni_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
