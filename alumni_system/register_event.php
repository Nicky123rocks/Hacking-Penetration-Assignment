<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['alumni_id'])) {
    echo "Access denied.";
    exit();
}
$alumni_id = $_SESSION['alumni_id'];
$event_id = $_GET['event_id'];
$check = $conn->query("SELECT * FROM event_registrations WHERE alumni_id=$alumni_id AND event_id=$event_id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Event Registration</h2>
    <?php
    if ($check->num_rows > 0) {
        echo "You already registered for this event.";
    } else {
        $conn->query("INSERT INTO event_registrations (alumni_id, event_id) VALUES ($alumni_id, $event_id)");
        echo "Successfully registered!";
    }
    ?>
    <a href="view_events.php" class="back-btn">Back to Events</a>
</div>
</body>
</html>
