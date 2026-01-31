<?php
/**
 * Delete Product
 * VULNERABILITIES: CSRF, IDOR, No confirmation
 */
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// VULNERABILITY: No CSRF token validation, IDOR
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Direct deletion without proper validation
    $query = "DELETE FROM products WHERE id='$id'";
    
    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php?msg=Product deleted successfully");
    } else {
        header("Location: dashboard.php?error=Failed to delete product");
    }
} else {
    header("Location: dashboard.php");
}
exit();
?>
