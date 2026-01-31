<?php
include 'db_connect.php';
$mentors = $conn->query("SELECT m.*, a.name FROM mentors m JOIN alumni a ON m.alumni_id = a.id ORDER BY m.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Mentorships</title>
 <style>
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #d4fc79, #96e6a1);
    margin: 0;
    padding: 0;
    color: #2e3c61;
  }

  .container {
    max-width: 900px;
    margin: 40px auto;
    background: #ffffffdd;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
  }

  h2 {
    text-align: center;
    color: #33691e;
    font-size: 2.2em;
    margin-bottom: 30px;
  }

  .mentor-box {
    background: #f1f8e9;
    border-left: 6px solid #8bc34a;
    padding: 18px;
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
  }

  ul {
    padding-left: 20px;
  }

  .back-btn {
    display: inline-block;
    background-color: #558b2f;
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.3s;
  }

  .back-btn:hover {
    background-color: #33691e;
  }
</style>

</head>
<body>
<div class="container">
  <h2>Mentorship Management</h2>
  <?php while ($m = $mentors->fetch_assoc()):
    $participants = $conn->query("SELECT mp.*, a.name FROM mentorship_participants mp JOIN alumni a ON mp.alumni_id = a.id WHERE mp.mentorship_id = {$m['id']}");
  ?>
    <div class="mentor-box">
      <strong><?= $m['topic'] ?> by <?= $m['name'] ?></strong>
      <p><?= $m['description'] ?></p>
      <ul>
        <?php while ($p = $participants->fetch_assoc()): ?>
          <li><?= $p['name'] ?> <a href="remove_participant.php?id=<?= $p['id'] ?>">Remove</a></li>
        <?php endwhile; ?>
      </ul>
      <a href="delete_mentor.php?id=<?= $m['id'] ?>">Remove Mentor</a>
    </div>
  <?php endwhile; ?>
  <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
