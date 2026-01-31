<?php
/**
 * Database Configuration File
 * TechNovation Solutions - Vulnerable E-Commerce Platform
 * 
 * WARNING: This application contains intentional vulnerabilities for educational purposes.
 * DO NOT deploy in production environments.
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'technovation');

// Create database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Set character set to UTF-8
mysqli_set_charset($conn, "utf8mb4");

// VULNERABILITY: Error display enabled (Information Disclosure)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
