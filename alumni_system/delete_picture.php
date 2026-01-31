<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['alumni_id'])) {
    echo "Access denied.";
    exit();
}

$id = $_SESSION['alumni_id'];


$sql = "SELECT profile_picture FROM alumni WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($row['profile_picture']) {
    $file_path = "uploads/" . $row['profile_picture'];
    if (file_exists($file_path)) {
        unlink($file_path); 
    }
    $conn->query("UPDATE alumni SET profile_picture = NULL WHERE id = $id");
}

header("Location: update_profile.php");
?>
