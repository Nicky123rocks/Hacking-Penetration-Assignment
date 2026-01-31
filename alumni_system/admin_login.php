<?php
include 'db_connect.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];


$result = $conn->query("SELECT * FROM admins WHERE username = '$username'");

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();

    
    if (password_verify($password, $admin['password'])) {
        
        $_SESSION['admin_id'] = $admin['id'];

        
        header("Location: admin_dashboard.php");
        exit();
    }
}


echo "<script>alert('Invalid username or password'); window.history.back();</script>";
?>
