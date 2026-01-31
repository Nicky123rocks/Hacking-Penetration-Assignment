<?php
session_start();
if (!isset($_SESSION['alumni_id'])) exit("Access denied.");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Change Password</title>
  <style>
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #fddb92, #d1fdff);
    margin: 0;
    padding: 0;
    color: #444;
  }

  .container {
    max-width: 700px;
    margin: 60px auto;
    background: #ffffffee;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  }

  h2 {
    text-align: center;
    font-size: 2.4em;
    color: #e65100;
    margin-bottom: 25px;
    text-shadow: 1px 1px #ffe0b2;
  }

  input {
    width: 100%;
    padding: 14px;
    margin-bottom: 18px;
    border-radius: 7px;
    border: 1px solid #bbb;
    font-size: 1em;
  }

  input[type="submit"], .back-btn {
    background: #ff8f00;
    color: white;
    padding: 12px 24px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.3s ease;
    text-decoration: none;
    display: inline-block;
  }

  .back-btn {
    background-color: #6a1b9a;
  }

  input[type="submit"]:hover {
    background-color: #ef6c00;
  }

  .back-btn:hover {
    background-color: #4a148c;
  }
</style>

</head>
<body>
<div class="container">
  <h2>Change Password</h2>
  <form method="POST" action="change_password_process.php">
    Current Password: <input type="password" name="current_password" required>
    New Password: <input type="password" name="new_password" required>
    Confirm New Password: <input type="password" name="confirm_password" required>
    <input type="submit" value="Change Password">
  </form>
  <a href="alumni_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
