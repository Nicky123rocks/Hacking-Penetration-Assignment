<?php
/**
 * User Registration Page
 * VULNERABILITIES: Weak Password Policy, No Email Verification, SQL Injection
 */
session_start();
include "../config/db.php";

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 4) {
        // VULNERABILITY: Weak password policy
        $error = "Password must be at least 4 characters!";
    } else {
        // Check if username exists
        $check_query = "SELECT * FROM users WHERE username='$username'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username already exists!";
        } else {
            // VULNERABILITY: Plain text password storage, SQL Injection
            $insert_query = "INSERT INTO users (username, email, password, role) 
                           VALUES ('$username', '$email', '$password', 'user')";
            
            if (mysqli_query($conn, $insert_query)) {
                $success = "Registration successful! You can now login.";
                // Auto-login (VULNERABILITY: No email verification)
                $_SESSION['user'] = $username;
                header("refresh:2;url=../index.php");
            } else {
                $error = "Registration failed! Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TechNovation Solutions</title>
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
                    <li><a href="login.php">Login</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="auth-container">
            <div class="auth-box">
                <h2>Create Your Account</h2>
                
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
                               placeholder="Choose a username"
                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required 
                               placeholder="Enter your email"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Choose a password">
                        <small>Minimum 4 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               placeholder="Confirm your password">
                    </div>
                    
                    <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                </form>
                
                <div class="auth-footer">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
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
