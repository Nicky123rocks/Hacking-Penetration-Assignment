<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['alumni_id'])) {
    echo "Access denied.";
    exit();
}

$id = $_SESSION['alumni_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$year = $_POST['graduation_year'];
$course = $_POST['course'];

$upload_dir = "uploads/";
$profile_picture = $_FILES['profile_picture']['name'];

if (!empty($profile_picture)) {
    $target = $upload_dir . basename($profile_picture);
    move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target);

    $sql = "UPDATE alumni SET 
            name='$name', email='$email', graduation_year=$year, 
            course='$course', profile_picture='$profile_picture'
            WHERE id=$id";
} else {
    $sql = "UPDATE alumni SET 
            name='$name', email='$email', graduation_year=$year, 
            course='$course' WHERE id=$id";
}

if ($conn->query($sql) === TRUE) {
    echo "Profile updated successfully. <a href='alumni_dashboard.php'>Back</a>";
} else {
    echo "Error: " . $conn->error;
}
?>
