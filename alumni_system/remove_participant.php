<?php
include 'db_connect.php';
session_start();


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID.");
}

$id = (int)$_GET['id']; 


$stmt = $conn->prepare("DELETE FROM mentorship_participants WHERE id = ?");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    die("Execution failed: " . $stmt->error);
}
$stmt->close();


header("Location: admin_mentorship.php");
exit();
?>
