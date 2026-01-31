<!DOCTYPE html>
<html>
<head>
    <title>Create Alumni Account</title>
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
    <h2>Create New Alumni Account</h2>
    <form method="POST" action="create_alumni_process.php">
        <label>Name:</label>
        <input type="text" name="name" required>
        
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <label>Graduation Year:</label>
        <input type="number" name="graduation_year" min="1900" max="2099" required>
        
        <label>Course:</label>
        <input type="text" name="course" required>

        <input type="submit" value="Create Alumni">
    </form>
    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
