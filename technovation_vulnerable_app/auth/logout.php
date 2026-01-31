<?php
/**
 * Logout Page
 * VULNERABILITY: No CSRF protection, session not properly destroyed
 */
session_start();

// VULNERABILITY: Incomplete session cleanup
unset($_SESSION['user']);
unset($_SESSION['user_id']);
// Note: session_destroy() not called, cookies not cleared

header("Location: ../index.php");
exit();
?>
