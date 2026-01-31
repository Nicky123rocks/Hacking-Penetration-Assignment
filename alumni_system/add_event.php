<!DOCTYPE html>
<html>
<head>
  <title>Add Event</title>
 <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #ffecd2, #fcb69f);
    margin: 0;
    padding: 30px;
    color: #333;
  }

  .container {
    max-width: 700px;
    margin: auto;
    background: #ffffffcc;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    color: #d81b60;
    margin-bottom: 25px;
    font-size: 2.2em;
  }

  input, textarea {
    width: 100%;
    padding: 12px;
    margin-top: 8px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-sizing: border-box;
    transition: 0.3s;
  }

  input:focus, textarea:focus {
    border-color: #ff4081;
    outline: none;
  }

  input[type="submit"], .back-btn {
    background: #8e24aa;
    color: white;
    padding: 12px 20px;
    text-decoration: none;
    border: none;
    border-radius: 10px;
    display: inline-block;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s ease;
  }

  input[type="submit"]:hover, .back-btn:hover {
    background: #6a1b9a;
  }
</style>

</head>
<body>
<div class="container">
  <h2>Add New Event</h2>
  <form method="POST" action="add_event_process.php">
    Title: <input type="text" name="title" required>
    Description: <textarea name="description" required></textarea>
    Date: <input type="date" name="event_date" required>
    <input type="submit" value="Add Event">
  </form>
  <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
