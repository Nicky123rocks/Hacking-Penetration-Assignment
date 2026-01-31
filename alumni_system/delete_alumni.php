<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    echo "Access denied.";
    exit();
}


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    
    $conn->query("DELETE FROM donations WHERE alumni_id = $id");

    
    $conn->query("DELETE FROM alumni WHERE id = $id");

    header("Location: view_alumni.php");
    exit();
} else {
    echo "Invalid alumni ID.";
}
?>
