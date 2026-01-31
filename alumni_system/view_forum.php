<?php
include 'db_connect.php';
session_start();



$alumni_id = $_SESSION['alumni_id'] ?? null;
$admin_id = $_SESSION['admin_id'] ?? null;
$forum_id = $_GET['id'];

$stmt = $conn->prepare("SELECT f.*, a.name FROM forums f JOIN alumni a ON f.alumni_id = a.id WHERE f.id = ?");
$stmt->bind_param("i", $forum_id);
$stmt->execute();
$forum = $stmt->get_result()->fetch_assoc();
$stmt->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment'])) {
    $comment = htmlspecialchars(trim($_POST['comment']), ENT_QUOTES, 'UTF-8');

    if ($alumni_id) {
        $user_id = $alumni_id;
    } elseif ($admin_id) {
        $user_id = 0; 
    }

    $stmt = $conn->prepare("INSERT INTO forum_comments (forum_id, alumni_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $forum_id, $user_id, $comment);
    $stmt->execute();
    $stmt->close();

    header("Location: view_forum.php?id=$forum_id");
    exit;
}


$comments = $conn->prepare("SELECT c.*, a.name FROM forum_comments c LEFT JOIN alumni a ON c.alumni_id = a.id WHERE c.forum_id = ? ORDER BY c.commented_at ASC");
$comments->bind_param("i", $forum_id);
$comments->execute();
$result = $comments->get_result();
$comments->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forum Discussion</title>
    <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #ffecd2, #fcb69f);
    margin: 0;
    padding: 30px;
    color: #333;
  }

  .container {
    max-width: 800px;
    margin: auto;
    background: #ffffffee;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    color: #c62828;
    font-size: 2.2em;
    margin-bottom: 30px;
  }

  .forum-post {
    background-color: #fce4ec;
    border-left: 6px solid #f06292;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 12px;
  }

  .comment-box {
    background: #fff3e0;
    border: 1px solid #ffc107;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
  }

  textarea {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ccc;
  }

  input[type="submit"] {
    background-color: #e91e63;
    color: white;
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
  }

  input[type="submit"]:hover {
    background-color: #c2185b;
  }
</style>

</head>
<body>
<div class="container">
    <h2><?= htmlspecialchars($forum['title']) ?></h2>
    <p><strong>By <?= htmlspecialchars($forum['name']) ?></strong> - <?= $forum['created_at'] ?></p>
    <p><?= htmlspecialchars($forum['content']) ?></p>

    <h3>Leave a Reply</h3>
    <form method="POST">
        <textarea name="comment" rows="4" required></textarea>
        <input type="submit" value="Post Reply">
    </form>

    <h3>Replies</h3>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="comment">
            <p><strong><?= $row['name'] ?? 'Admin' ?>:</strong> <?= htmlspecialchars($row['comment']) ?></p>
            <small><em><?= $row['commented_at'] ?></em></small>
        </div>
    <?php endwhile; ?>

    <a class="back" href="logout.php">← Logout of System</a>
</div>
</body>
</html>
