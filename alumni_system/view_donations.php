<?php
include 'db_connect.php';
$sql = "SELECT d.*, a.name, a.email FROM donations d JOIN alumni a ON d.alumni_id = a.id ORDER BY d.donated_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Donations</title>
 <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #a1c4fd, #c2e9fb);
    margin: 0;
    padding: 30px;
    color: #333;
  }

  .container {
    max-width: 960px;
    margin: auto;
    background: #ffffffdd;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    color: #3949ab;
    font-size: 2.2em;
    margin-bottom: 30px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  th, td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
    background: #f9fbe7;
  }

  th {
    background: #3949ab;
    color: white;
  }

  tr:hover td {
    background-color: #e8eaf6;
  }

  img {
    max-width: 60px;
    height: auto;
    border-radius: 8px;
  }

  .back-btn {
    display: inline-block;
    background-color: #5c6bc0;
    color: white;
    padding: 10px 18px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: background 0.3s ease;
  }

  .back-btn:hover {
    background-color: #3f51b5;
  }
</style>

</head>
<body>
<div class="container">
  <h2>All Donations</h2>
  <?php if ($result->num_rows > 0): ?>
  <table>
    <tr>
      <th>Name</th><th>Email</th><th>Amount</th><th>Description</th><th>Proof</th><th>Date</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['name'] ?></td>
      <td><?= $row['email'] ?></td>
      <td>RM <?= $row['amount'] ?></td>
      <td><?= $row['description'] ?></td>
      <td><img src="uploads/<?= $row['transaction_image'] ?>"></td>
      <td><?= $row['donated_at'] ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
  <?php else: ?>
    <p>No donations recorded yet.</p>
  <?php endif; ?>
  <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
