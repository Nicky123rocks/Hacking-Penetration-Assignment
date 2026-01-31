<?php
include 'db_connect.php';
session_start();
$alumni_id = $_SESSION['alumni_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topic'])) {
    $topic = $_POST['topic'];
    $desc = $_POST['description'];
    $conn->query("INSERT INTO mentors (alumni_id, topic, description) VALUES ($alumni_id, '$topic', '$desc')");
}


if (isset($_GET['join'])) {
    $mid = $_GET['join'];
    $count = $conn->query("SELECT COUNT(*) AS total FROM mentorship_participants WHERE mentorship_id=$mid")->fetch_assoc()['total'];
    if ($count < 5) {
        $exists = $conn->query("SELECT * FROM mentorship_participants WHERE mentorship_id=$mid AND alumni_id=$alumni_id");
        if ($exists->num_rows === 0) {
            $conn->query("INSERT INTO mentorship_participants (mentorship_id, alumni_id) VALUES ($mid, $alumni_id)");
        }
    } else {
        echo "<script>alert('This mentorship is full!');</script>";
    }
}

$mentors = $conn->query("SELECT m.*, a.name FROM mentors m JOIN alumni a ON m.alumni_id = a.id ORDER BY m.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mentorship Program</title>
    <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #c1dfc4, #deecdd);
    margin: 0;
    padding: 0;
    color: #2e7d32;
  }

  .container {
    max-width: 850px;
    margin: 60px auto;
    background: #ffffffee;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    font-size: 2.4em;
    color: #1b5e20;
    margin-bottom: 25px;
    text-shadow: 1px 1px #a5d6a7;
  }

  input, textarea {
    width: 100%;
    padding: 14px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1em;
  }

  input[type="submit"], .back-btn, a {
    background: #43a047;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
    transition: background 0.3s ease;
  }

  input[type="submit"]:hover, .back-btn:hover {
    background-color: #2e7d32;
  }

  .mentorship-entry {
    background: #f1f8e9;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
  }
</style>

</head>
<body>
<div class="container">
<h2>Mentorship Program</h2>

<form method="POST">
    <input type="text" name="topic" placeholder="Mentorship Topic" required>
    <textarea name="description" placeholder="Mentorship Description" required></textarea>
    <input type="submit" value="Become a Mentor">
</form>

<h3>Available Mentorships</h3>
<?php while ($m = $mentors->fetch_assoc()): ?>
    <div>
        <h4><?= $m['topic'] ?> by <?= $m['name'] ?></h4>
        <p><?= $m['description'] ?></p>
        <a href="mentorship.php?join=<?= $m['id'] ?>">Join</a>
    </div>
<?php endwhile; ?>

<a href="alumni_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
