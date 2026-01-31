<?php
/**
 * User Login Page
 * VULNERABILITIES: SQL Injection, Weak Password Storage, Session Fixation
 */
session_start();

// VULNERABILITY: Session Fixation - accepting session ID from URL
if (isset($_GET['PHPSESSID'])) {
    session_id($_GET['PHPSESSID']);
}

include "../config/db.php";

$error = '';
$success = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // VULNERABILITY: SQL Injection - no prepared statements
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // VULNERABILITY: Insufficient session validation
        $_SESSION['user'] = $username;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect based on role
        if ($user['role'] == 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TechNovation Solutions</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <div class="logo">
                    <h1>🚀 TechNovation Solutions</h1>
                </div>
                <ul class="nav-menu">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="register.php">Register</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="auth-container">
            <div class="auth-box">
                <h2>Login to Your Account</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="" class="auth-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required 
                               placeholder="Enter your username">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Enter your password">
                    </div>
                    
                    <!-- VULNERABILITY: Remember me without secure implementation -->
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    
                    <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                </form>
                
                <div class="auth-footer">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                    <p><a href="forgot_password.php">Forgot Password?</a></p>
                </div>
                
                <!-- VULNERABILITY: Information disclosure -->
                <div class="debug-hint">
                    <small>Hint: Try SQL injection or check default credentials</small>
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
