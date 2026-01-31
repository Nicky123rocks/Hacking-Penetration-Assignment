<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['alumni_id'])) {
    echo "Access denied.";
    exit();
}

$id = $_SESSION['alumni_id'];
$current = $_POST['current_password'];
$new = $_POST['new_password'];
$confirm = $_POST['confirm_password'];


$stmt = $conn->prepare("SELECT password FROM alumni WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($hashedPassword);
$stmt->fetch();
$stmt->close();

if (!password_verify($current, $hashedPassword)) {
    $message = "Incorrect current password.";
} elseif ($new !== $confirm) {
    $message = "New passwords do not match.";
} else {
    
    $newHashed = password_hash($new, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE alumni SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $newHashed, $id);
    if ($stmt->execute()) {
        $message = "Password changed successfully.";
    } else {
        $message = "Error updating password.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Change Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Password Change Result</h2>
    <p><?= htmlspecialchars($message) ?></p>
    <a href="alumni_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
