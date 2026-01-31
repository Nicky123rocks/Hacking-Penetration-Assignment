<?php
include 'db_connect.php';
$result = $conn->query("SELECT * FROM alumni");
?>
<!DOCTYPE html>
<html>
<head>
  <title>View Alumni</title>
  <style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #fdfcfb, #e2d1c3);
    margin: 0;
    padding: 20px;
    color: #333;
  }

  .container {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
  }

  h2 {
    text-align: center;
    color: #6a1b9a;
    font-size: 2em;
    margin-bottom: 30px;
  }

  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
  }

  th, td {
    background: #f3e5f5;
    padding: 12px 15px;
    border: none;
    border-radius: 10px;
    text-align: left;
  }

  th {
    background: #8e24aa;
    color: white;
    font-weight: bold;
  }

  tr:hover td {
    background: #e1bee7;
  }

  .back-btn {
    display: inline-block;
    background-color: #3949ab;
    color: white;
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 10px;
    margin-top: 30px;
    font-weight: bold;
    transition: background 0.3s ease;
  }

  .back-btn:hover {
    background-color: #303f9f;
  }
</style>

</head>
<body>
<div class="container">
  <h2>Registered Alumni</h2>
  <table>
    <tr><th>Name</th><th>Email</th><th>Actions</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><a href="delete_alumni.php?id=<?= $row['id'] ?>">Delete</a></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
