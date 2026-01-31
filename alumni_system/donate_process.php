<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['alumni_id'])) {
    echo "Access denied.";
    exit();
}

$alumni_id = $_SESSION['alumni_id'];
$amount = $_POST['amount'];
$description = $_POST['description'];
$upload_dir = "uploads/";
$image_name = $_FILES['transaction_image']['name'];

if (!empty($image_name)) {
    $target = $upload_dir . basename($image_name);
    move_uploaded_file($_FILES['transaction_image']['tmp_name'], $target);

    $sql = "INSERT INTO donations (alumni_id, amount, description, transaction_image)
            VALUES ($alumni_id, '$amount', '$description', '$image_name')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='container'><h2>Thank you!</h2><p>Your donation has been submitted.</p>
        <a href='alumni_dashboard.php' class='back-btn'>Back to Dashboard</a></div>";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Image upload failed.";
}
?>
