<?php
/**
 * Admin Login Page
 * VULNERABILITIES: SQL Injection, Weak Authentication, Brute Force
 */
session_start();
include "../config/db.php";

$error = '';

// VULNERABILITY: No rate limiting, vulnerable to brute force
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // VULNERABILITY: SQL Injection
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' AND role='admin'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
        $_SESSION['admin'] = $username;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['role'] = 'admin';
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid admin credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - TechNovation Solutions</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <div class="logo">
                    <h1>🚀 TechNovation Solutions - Admin</h1>
                </div>
                <ul class="nav-menu">
                    <li><a href="../index.php">Home</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="auth-container">
            <div class="auth-box">
                <h2>Admin Login</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="" class="auth-form">
                    <div class="form-group">
                        <label for="username">Admin Username</label>
                        <input type="text" id="username" name="username" required 
                               placeholder="Enter admin username">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Admin Password</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Enter admin password">
                    </div>
                    
                    <button type="submit" name="login" class="btn btn-primary btn-block">Admin Login</button>
                </form>
                
                <div class="debug-hint">
                    <small>Default credentials might work... Try admin/admin123</small>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 TechNovation Solutions. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
