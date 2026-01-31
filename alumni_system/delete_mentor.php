<?php
include 'db_connect.php';
session_start();


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID.");
}

$id = (int)$_GET['id'];


$stmt1 = $conn->prepare("DELETE FROM mentorship_participants WHERE mentorship_id = ?");
$stmt1->bind_param("i", $id);
$stmt1->execute();
$stmt1->close();

$stmt2 = $conn->prepare("DELETE FROM mentors WHERE id = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$stmt2->close();


header("Location: admin_mentorship.php");
exit();
?>
