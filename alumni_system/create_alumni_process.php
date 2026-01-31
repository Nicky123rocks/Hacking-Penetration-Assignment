<?php
include 'db_connect.php';


$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$graduation_year = $_POST['graduation_year'];
$course = $_POST['course'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);


$check = $conn->query("SELECT * FROM alumni WHERE email = '$email'");
if ($check->num_rows > 0) {
    echo "<script>alert('An account with this email already exists.'); window.history.back();</script>";
    exit();
}


$sql = "INSERT INTO alumni (name, email, password, graduation_year, course)
        VALUES ('$name', '$email', '$hashed_password', $graduation_year, '$course')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Alumni account created successfully.'); window.location='admin_dashboard.php';</script>";
} else {
    echo "Error: " . $conn->error;
}
?>
