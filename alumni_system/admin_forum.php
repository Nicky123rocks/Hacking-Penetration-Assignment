<?php
include 'db_connect.php';
$forums = $conn->query("SELECT f.*, a.name FROM forums f JOIN alumni a ON f.alumni_id = a.id ORDER BY f.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Forums</title>
 <style>
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #fbd3e9, #bb377d);
    margin: 0;
    padding: 0;
    color: #333;
  }

  .container {
    max-width: 900px;
    margin: 40px auto;
    background: #ffffffcc;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    color: #880e4f;
    font-size: 2.2em;
    margin-bottom: 30px;
  }

  .forum-box {
    background: #fce4ec;
    border-left: 8px solid #ec407a;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
  }

  .back-btn {
    display: inline-block;
    background-color: #880e4f;
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.3s;
  }

  .back-btn:hover {
    background-color: #ad1457;
  }
</style>

</head>
<body>
<div class="container">
  <h2>Manage Forums</h2>
  <?php while ($row = $forums->fetch_assoc()): ?>
    <div class="forum-box">
      <h4><?= $row['title'] ?> by <?= $row['name'] ?></h4>
      <p><?= $row['content'] ?></p>
      <a href="view_forum.php?id=<?= $row['id'] ?>">Reply</a> |
      <a href="delete_forum.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this forum?')">Delete</a>
    </div>
  <?php endwhile; ?>
  <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
