<?php
/**
 * Main Product Listing Page
 * VULNERABILITIES: SQL Injection in search, XSS in output
 */
session_start();
include "config/db.php";

// Default query to get all products
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");

// VULNERABILITY: SQL Injection in search parameter
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // Direct concatenation - vulnerable to SQL injection
    $query = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
    $result = mysqli_query($conn, $query);
}

// Get user session info
$user_logged_in = isset($_SESSION['user']);
$username = $user_logged_in ? $_SESSION['user'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNovation Solutions - Your Tech Partner</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <div class="logo">
                    <h1>🚀 TechNovation Solutions</h1>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="api/products.php">API</a></li>
                    <?php if ($user_logged_in): ?>
                        <li><a href="#">Welcome, <?php echo $username; ?></a></li>
                        <li><a href="auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="auth/login.php">Login</a></li>
                        <li><a href="auth/register.php">Register</a></li>
                    <?php endif; ?>
                    <li><a href="admin/login.php">Admin</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
        <section class="hero">
            <h2>Welcome to TechNovation Solutions Store</h2>
            <p>Your one-stop shop for the latest technology products</p>
        </section>

        <section class="search-section">
            <form method="GET" action="index.php" class="search-form">
                <input type="text" name="search" placeholder="Search products..." 
                       value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" 
                       class="search-input">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            
            <?php if (isset($_GET['search'])): ?>
                <!-- VULNERABILITY: XSS - Reflected output without sanitization -->
                <p class="search-result">Search results for: <strong><?php echo $_GET['search']; ?></strong></p>
            <?php endif; ?>
        </section>

        <section class="products-section">
            <h3>Our Products</h3>
            <div class="products-grid">
                <?php 
                if ($result && mysqli_num_rows($result) > 0):
                    while($row = mysqli_fetch_assoc($result)): 
                ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="assets/images/product-placeholder.png" alt="<?php echo $row['name']; ?>" 
                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22200%22 height=%22200%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-size=%2218%22 text-anchor=%22middle%22 alignment-baseline=%22middle%22 font-family=%22monospace%22 fill=%22%23999%22%3EProduct Image%3C/text%3E%3C/svg%3E'">
                        </div>
                        <div class="product-info">
                            <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                            <p class="product-description">
                                <?php echo substr(htmlspecialchars($row['description']), 0, 100); ?>...
                            </p>
                            <p class="product-price">RM <?php echo number_format($row['price'], 2); ?></p>
                            <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">View Details</a>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <p class="no-results">No products found.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- VULNERABILITY: Debugging information exposed -->
        <?php if (isset($_GET['debug'])): ?>
        <section class="debug-info">
            <h4>Debug Information</h4>
            <pre>
Server Info: <?php echo $_SERVER['SERVER_SOFTWARE']; ?>
PHP Version: <?php echo phpversion(); ?>
Database: <?php echo DB_NAME; ?>
Query: <?php echo isset($query) ? $query : 'Default query'; ?>
            </pre>
        </section>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 TechNovation Solutions. All rights reserved.</p>
            <p>Educational Purpose Only - Contains Intentional Vulnerabilities</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
