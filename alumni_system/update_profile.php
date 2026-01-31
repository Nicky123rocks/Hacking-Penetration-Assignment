<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['alumni_id'])) exit("Access denied.");
$id = $_SESSION['alumni_id'];
$row = $conn->query("SELECT * FROM alumni WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Update Profile</title>
  <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #d4fc79, #96e6a1);
    margin: 0;
    padding: 0;
    color: #333;
  }

  .container {
    max-width: 750px;
    margin: 60px auto;
    background: #ffffffdd;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    color: #388e3c;
    font-size: 2.5em;
    margin-bottom: 30px;
    text-shadow: 1px 1px #c8e6c9;
  }

  input, textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1em;
  }

  input[type="submit"], .back-btn {
    background-color: #66bb6a;
    color: white;
    padding: 12px 20px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-right: 10px;
    transition: background 0.3s ease;
  }

  input[type="submit"]:hover, .back-btn:hover {
    background-color: #388e3c;
  }

  .back-btn {
    text-decoration: none;
    background-color: #8d6e63;
  }
</style>

</head>
<body>
<div class="container">
  <h2>Update Profile</h2>
  <form method="POST" action="update_profile_process.php" enctype="multipart/form-data">
    Name: <input type="text" name="name" value="<?= $row['name'] ?>" required>
    Email: <input type="email" name="email" value="<?= $row['email'] ?>" required>
    Graduation Year: <input type="number" name="graduation_year" value="<?= $row['graduation_year'] ?>" required>
    Course: <input type="text" name="course" value="<?= $row['course'] ?>" required>
    Profile Picture: <input type="file" name="profile_picture"><br>
    <?php if (!empty($row['profile_picture'])) echo "<img src='uploads/{$row['profile_picture']}' width='100'><br>"; ?>
    <input type="submit" value="Update Profile">
  </form>
  <a href="alumni_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
