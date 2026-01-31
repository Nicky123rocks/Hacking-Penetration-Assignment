<?php
/**
 * Checkout Page
 * VULNERABILITIES: CSRF, Price Manipulation, Insecure Direct Object Reference
 */
session_start();
include "config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $_SESSION['user_id'];
$success = false;
$order_id = null;

// VULNERABILITY: No CSRF token validation
if (isset($_POST['checkout'])) {
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        // Calculate total
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['total'];
        }
        
        // Insert order
        $order_query = "INSERT INTO orders (username, user_id, total_amount, order_date, status) 
                       VALUES ('$user', '$user_id', '$total', NOW(), 'pending')";
        
        if (mysqli_query($conn, $order_query)) {
            $order_id = mysqli_insert_id($conn);
            
            // Insert order items
            foreach ($_SESSION['cart'] as $item) {
                $item_query = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal) 
                              VALUES ('$order_id', '{$item['product_id']}', '{$item['product']}', 
                                     '{$item['price']}', '{$item['quantity']}', '{$item['total']}')";
                mysqli_query($conn, $item_query);
            }
            
            $success = true;
            // Clear cart
            unset($_SESSION['cart']);
        }
    }
}

// Get cart items
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$cart_total = 0;
foreach ($cart_items as $item) {
    $cart_total += $item['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TechNovation Solutions</title>
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
                    <li><a href="#">Welcome, <?php echo htmlspecialchars($user); ?></a></li>
                    <li><a href="auth/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="checkout-container">
            <h2>Checkout</h2>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <h3>✓ Order Placed Successfully!</h3>
                    <p>Your order #<?php echo $order_id; ?> has been confirmed.</p>
                    <p>Total Amount: RM <?php echo number_format($cart_total, 2); ?></p>
                    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                </div>
            <?php elseif (count($cart_items) == 0): ?>
                <div class="alert alert-info">
                    <p>Your cart is empty!</p>
                    <a href="index.php" class="btn btn-primary">Browse Products</a>
                </div>
            <?php else: ?>
                <div class="cart-section">
                    <h3>Your Cart</h3>
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product']); ?></td>
                                <td>RM <?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>RM <?php echo number_format($item['total'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>Total</strong></td>
                                <td><strong>RM <?php echo number_format($cart_total, 2); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="checkout-form">
                    <h3>Payment Information</h3>
                    <!-- VULNERABILITY: No CSRF protection -->
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="card_number">Card Number</label>
                            <input type="text" id="card_number" name="card_number" 
                                   placeholder="1234 5678 9012 3456" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="expiry">Expiry Date</label>
                                <input type="text" id="expiry" name="expiry" 
                                       placeholder="MM/YY" required>
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" 
                                       placeholder="123" required>
                            </div>
                        </div>
                        
                        <button type="submit" name="checkout" class="btn btn-primary btn-large btn-block">
                            Place Order - RM <?php echo number_format($cart_total, 2); ?>
                        </button>
                    </form>
                    
                    <!-- VULNERABILITY: Hidden debug parameter -->
                    <?php if (isset($_GET['debug'])): ?>
                    <div class="debug-info">
                        <h4>Debug Information</h4>
                        <pre><?php print_r($_SESSION); ?></pre>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 TechNovation Solutions. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
