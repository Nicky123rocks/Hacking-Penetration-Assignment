<?php
session_start();
include 'db_connect.php';



$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    if (empty($username) || empty($password)) {
        $message = "Please fill all fields.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        
        $stmt = $conn->prepare("SELECT id FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username already exists.";
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);
            if ($stmt->execute()) {
                $message = "New admin account created successfully.";
            } else {
                $message = "Error creating admin.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Admin</title>
  <style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #ffecd2, #fcb69f);
    margin: 0;
    padding: 40px;
    color: #333;
  }

  h1, h2 {
    text-align: center;
    color: #4a148c;
  }

  .container {
    max-width: 600px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  }

  label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
    color: #6a1b9a;
  }

  input[type="text"],
  input[type="password"],
  input[type="email"],
  select {
    width: 100%;
    padding: 12px;
    margin-top: 6px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-sizing: border-box;
    transition: all 0.3s ease;
  }

  input[type="text"]:focus,
  input[type="password"]:focus,
  input[type="email"]:focus,
  select:focus {
    border-color: #7e57c2;
    outline: none;
  }

  input[type="submit"] {
    background: #8e24aa;
    color: white;
    padding: 14px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    transition: background 0.3s ease;
  }

  input[type="submit"]:hover {
    background: #6a1b9a;
  }

  .back-btn {
    display: inline-block;
    margin-top: 15px;
    text-decoration: none;
    background: #6c757d;
    color: white;
    padding: 10px 16px;
    border-radius: 8px;
  }
</style>

</head>
<body>
<div class="container">
    <h2>Create New Admin</h2>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <input type="submit" value="Create Admin">
    </form>
    <?php if (!empty($message)): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>
	<a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>






