<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['alumni_id'])) exit("Access denied.");
$id = $_SESSION['alumni_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $conn->query("INSERT INTO forums (alumni_id, title, content) VALUES ($id, '$title', '$content')");
}

$forums = $conn->query("SELECT f.*, a.name FROM forums f JOIN alumni a ON f.alumni_id = a.id ORDER BY f.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Discussion Forum</title>
   <style>
  body {
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(to right, #a1c4fd, #c2e9fb);
    margin: 0;
    padding: 0;
    color: #333;
  }

  .container {
    max-width: 900px;
    margin: 50px auto;
    background: #ffffff;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    font-size: 2.5em;
    color: #1e88e5;
    margin-bottom: 30px;
  }

  form input[type="text"], form textarea {
    width: 100%;
    padding: 14px;
    margin-bottom: 18px;
    border-radius: 8px;
    border: 1px solid #bbb;
    font-size: 1em;
  }

  form input[type="submit"] {
    background-color: #1e88e5;
    color: white;
    padding: 12px 22px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  form input[type="submit"]:hover {
    background-color: #1565c0;
  }

  .forum-post {
    background: #f1f8ff;
    border-left: 5px solid #42a5f5;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
  }

  .forum-post h4 {
    margin: 0 0 8px 0;
    color: #0d47a1;
  }

  .back-btn {
    background-color: #6d4c41;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    display: inline-block;
    margin-top: 15px;
  }

  .back-btn:hover {
    background-color: #4e342e;
  }
</style>

</head>
<body>
<div class="container">
<h2>Discussion Forum</h2>

<form method="POST">
    <input type="text" name="title" placeholder="Forum Title" required>
    <textarea name="content" placeholder="Start a discussion..." required></textarea>
    <input type="submit" value="Create Forum">
</form>

<?php while ($row = $forums->fetch_assoc()): ?>
    <div style="margin-top:20px;">
        <h4><?= $row['title'] ?> <small>by <?= $row['name'] ?> on <?= $row['created_at'] ?></small></h4>
        <p><?= $row['content'] ?></p>
        <a href="view_forum.php?id=<?= $row['id'] ?>">View Discussion</a>
    </div>
<?php endwhile; ?>

<a href="alumni_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
