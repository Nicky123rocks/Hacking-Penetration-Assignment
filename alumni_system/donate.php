<?php
session_start();
if (!isset($_SESSION['alumni_id'])) exit("Access denied.");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Make a Donation</title>
 
   <style>
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to top right, #ffecd2, #fcb69f);
    margin: 0;
    padding: 0;
    color: #5d4037;
  }

  .container {
    max-width: 720px;
    margin: 50px auto;
    background: #fff5f5;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  }

  h2 {
    text-align: center;
    font-size: 2.5em;
    color: #d84315;
    margin-bottom: 30px;
    text-shadow: 1px 1px #ffccbc;
  }

  input, textarea, select {
    width: 100%;
    padding: 14px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #bbb;
    font-size: 1em;
  }

  input[type="submit"], .back-btn {
    background-color: #f06292;
    color: white;
    padding: 12px 22px;
    font-weight: bold;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    margin-top: 10px;
    text-decoration: none;
    transition: background-color 0.3s ease;
  }

  input[type="submit"]:hover {
    background-color: #ec407a;
  }

  .back-btn {
    background-color: #7e57c2;
  }

  .back-btn:hover {
    background-color: #5e35b1;
  }
</style>


 
</head>
<body>
  <div class="container">
    <h2>Make a Donation</h2>
    <form method="POST" action="donate_process.php" enctype="multipart/form-data">
      Donation Amount: <input type="number" name="amount" step="0.01" required>
      Description: <textarea name="description" required></textarea>
      Upload Proof (Image): <input type="file" name="transaction_image" accept="image/*" required>
      <input type="submit" value="Submit Donation">
    </form>
    <a href="alumni_dashboard.php" class="back-btn">Back to Dashboard</a>
  </div>
</body>
</html>
