<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logged Out</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb, #f5576c);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .message-box {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #2c3e50;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #6c5ce7;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        a:hover {
            background: #4834d4;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2>You have been logged out. Thank you</h2>
        <a href="index.html">Go to Home</a>
    </div>
</body>
</html>
