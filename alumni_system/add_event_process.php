<?php
include 'db_connect.php';

$title = $_POST['title'];
$desc = $_POST['description'];
$date = $_POST['event_date'];

$conn->query("INSERT INTO events (title, description, event_date)
VALUES ('$title', '$desc', '$date')");

echo "Event added successfully. <a href='admin_dashboard.php'>Back</a>";
?>
