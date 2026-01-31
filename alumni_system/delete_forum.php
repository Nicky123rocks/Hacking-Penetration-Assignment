<?php
include 'db_connect.php';
$id = $_GET['id'];
$conn->query("DELETE FROM forum_comments WHERE forum_id=$id");
$conn->query("DELETE FROM forums WHERE id=$id");
header("Location: admin_forum.php");
?>
