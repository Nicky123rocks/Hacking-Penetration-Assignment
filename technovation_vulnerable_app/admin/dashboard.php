<?php
/**
 * Admin Dashboard
 * VULNERABILITIES: Broken Access Control, IDOR, Command Injection
 */
session_start();
include "../config/db.php";

// VULNERABILITY: Weak authentication check
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$admin = $_SESSION['admin'];

// Get all products
$products_query = "SELECT * FROM products ORDER BY id DESC";
$products_result = mysqli_query($conn, $products_query);

// Get all orders
$orders_query = "SELECT o.*, u.email FROM orders o LEFT JOIN users u ON o.username = u.username ORDER BY o.id DESC LIMIT 10";
$orders_result = mysqli_query($conn, $orders_query);

// Get statistics
$stats_query = "SELECT 
                (SELECT COUNT(*) FROM products) as total_products,
                (SELECT COUNT(*) FROM orders) as total_orders,
                (SELECT COUNT(*) FROM users) as total_users,
                (SELECT SUM(total_amount) FROM orders) as total_revenue";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// VULNERABILITY: Command Injection via backup feature
if (isset($_GET['backup'])) {
    $filename = $_GET['filename'];
    // Direct command execution without validation
    $command = "mysqldump -u root technovation > backups/$filename.sql";
    system($command);
    echo "<script>alert('Backup created: $filename.sql');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TechNovation Solutions</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-admin">
            <div class="container">
                <div class="logo">
                    <h1>🔧 Admin Dashboard</h1>
                </div>
                <ul class="nav-menu">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="orders.php">Orders</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="#">Welcome, <?php echo htmlspecialchars($admin); ?></a></li>
                    <li><a href="../auth/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
        <h2>Dashboard Overview</h2>
        
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Products</h3>
                <p class="stat-number"><?php echo $stats['total_products']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Orders</h3>
                <p class="stat-number"><?php echo $stats['total_orders']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Users</h3>
                <p class="stat-number"><?php echo $stats['total_users']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <p class="stat-number">RM <?php echo number_format($stats['total_revenue'], 2); ?></p>
            </div>
        </div>

        <!-- Products Management -->
        <section class="admin-section">
            <div class="section-header">
                <h3>Products Management</h3>
                <a href="add_product.php" class="btn btn-primary">Add New Product</a>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($product = mysqli_fetch_assoc($products_result)): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>RM <?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo isset($product['stock']) ? $product['stock'] : 'N/A'; ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-small">Edit</a>
                            <!-- VULNERABILITY: No CSRF protection on delete -->
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" 
                               class="btn btn-small btn-danger" 
                               onclick="return confirm('Delete this product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Recent Orders -->
        <section class="admin-section">
            <div class="section-header">
                <h3>Recent Orders</h3>
                <a href="orders.php" class="btn btn-secondary">View All</a>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = mysqli_fetch_assoc($orders_result)): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['username']); ?></td>
                        <td>RM <?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                        <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                        <td>
                            <a href="view_order.php?id=<?php echo $order['id']; ?>" class="btn btn-small">View</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Admin Tools -->
        <section class="admin-section">
            <h3>Admin Tools</h3>
            <div class="tools-grid">
                <div class="tool-card">
                    <h4>Database Backup</h4>
                    <!-- VULNERABILITY: Command injection -->
                    <form method="GET" action="">
                        <input type="text" name="filename" placeholder="backup_name" value="backup_<?php echo date('Ymd'); ?>">
                        <button type="submit" name="backup" class="btn btn-secondary">Create Backup</button>
                    </form>
                </div>
                <div class="tool-card">
                    <h4>System Logs</h4>
                    <a href="logs.php" class="btn btn-secondary">View Logs</a>
                </div>
                <div class="tool-card">
                    <h4>Settings</h4>
                    <a href="settings.php" class="btn btn-secondary">Configure</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 TechNovation Solutions. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
