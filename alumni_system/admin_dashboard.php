<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4, #fad0c4, #fbc2eb, #a18cd1);
        }

        .navbar {
            display: flex;
            justify-content: center;
            background-color: linear-gradient(135deg, #ff9a9e, #fad0c4, #fad0c4, #fbc2eb, #a18cd1);
            padding: 10px 0;
            flex-wrap: wrap;
        }

        .navbar a {
            text-decoration: none;
            color: black;
            background-color: #7d5fff;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 8px;
            font-weight: bold;
        }

        .navbar a:hover {
            background-color: #B3D9D9;
        }

        .carousel-container {
            width: 80%;
            margin: 30px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .carousel-images {
            display: flex;
            width: 100%;
            animation: slide 15s infinite;
        }

        .carousel-images img {
            width: 100%;
            height: 700px;
            object-fit: cover;
        }

        @keyframes slide {
            0% { margin-left: 0; }
            33.33% { margin-left: 0; }
            38% { margin-left: -100%; }
            66.66% { margin-left: -100%; }
            71.66% { margin-left: -200%; }
            100% { margin-left: -200%; }
        }
		
		.page-title {
			position: absolute;
			top: 15px;
			left: 20px;
			font-size: 28px;
			font-weight: bold;
			background: linear-gradient(to right, #FFFF00, #0000FF);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}

        .logout {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .logout a {
            text-decoration: none;
            color: white;
            background-color: #ff6b6b;
            padding: 10px 30px;
            border-radius: 8px;
            font-weight: bold;
        }

        .logout a:hover {
            background-color: #ff8787;
        }
    </style>
</head>
<body>
<div class="page-title">Admin Dashboard</div>
<div class="navbar">
    <a href="view_alumni.php">View Alumni</a>
    <a href="create_alumni.php">Create Alumni Account</a>
    <a href="create_admin.php">Create New Admin</a>
    <a href="add_event.php">Add Event</a>
    <a href="view_donations.php">View Donations</a>
    <a href="admin_forum.php">Manage Forums</a>
    <a href="admin_mentorship.php">Manage Mentorships</a>
    <a href="admin_report.php">View Reports</a>
</div>

<div class="carousel-container">
    <div class="carousel-images">
        <img src="images/image1.png" alt="Slide 1">
        <img src="images/image2.jpg" alt="Slide 2">
        <img src="images/image3.jpg" alt="Slide 3">
    </div>
</div>

<div class="logout">
    <a href="logout.php">Logout</a>
</div>

</body>
</html>
