<?php
/**
 * Product Detail Page
 * VULNERABILITIES: SQL Injection, XSS, IDOR
 */
session_start();
include "config/db.php";

// VULNERABILITY: SQL Injection - no input validation
$id = isset($_GET['id']) ? $_GET['id'] : 1;

// Direct query without parameterization
$query = "SELECT * FROM products WHERE id='$id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$product = mysqli_fetch_assoc($result);

// Get user session
$user_logged_in = isset($_SESSION['user']);
$username = $user_logged_in ? $_SESSION['user'] : '';

// VULNERABILITY: Stored XSS in comments (if comment parameter exists)
if (isset($_POST['submit_comment']) && $user_logged_in) {
    $comment = $_POST['comment'];
    $product_id = $_POST['product_id'];
    // No sanitization - vulnerable to XSS
    mysqli_query($conn, "INSERT INTO comments (product_id, username, comment, created_at) 
                         VALUES ('$product_id', '$username', '$comment', NOW())");
}

// Get comments for this product
$comments_query = "SELECT * FROM comments WHERE product_id='$id' ORDER BY created_at DESC";
$comments_result = mysqli_query($conn, $comments_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - TechNovation Solutions</title>
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
                    <?php if ($user_logged_in): ?>
                        <li><a href="#">Welcome, <?php echo $username; ?></a></li>
                        <li><a href="auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="auth/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="product-detail">
            <div class="product-image-large">
                <img src="assets/images/product-placeholder.png" alt="<?php echo htmlspecialchars($product['name']); ?>"
                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22400%22%3E%3Crect fill=%22%23ddd%22 width=%22400%22 height=%22400%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-size=%2224%22 text-anchor=%22middle%22 alignment-baseline=%22middle%22 font-family=%22monospace%22 fill=%22%23999%22%3EProduct Image%3C/text%3E%3C/svg%3E'">
            </div>
            <div class="product-details">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="price">RM <?php echo number_format($product['price'], 2); ?></p>
                <div class="description">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
                
                <?php if ($user_logged_in): ?>
                <form action="cart/add_to_cart.php" method="POST" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="product" value="<?php echo htmlspecialchars($product['name']); ?>">
                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                    <div class="quantity-selector">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="10">
                    </div>
                    <button type="submit" class="btn btn-primary btn-large">Add to Cart</button>
                </form>
                <?php else: ?>
                <p class="login-notice">Please <a href="auth/login.php">login</a> to add items to cart.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Reviews/Comments Section -->
        <div class="comments-section">
            <h3>Customer Reviews</h3>
            
            <?php if ($user_logged_in): ?>
            <div class="comment-form">
                <h4>Leave a Review</h4>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <textarea name="comment" rows="4" placeholder="Share your thoughts about this product..." required></textarea>
                    <button type="submit" name="submit_comment" class="btn btn-primary">Submit Review</button>
                </form>
            </div>
            <?php endif; ?>

            <div class="comments-list">
                <?php 
                if ($comments_result && mysqli_num_rows($comments_result) > 0):
                    while($comment = mysqli_fetch_assoc($comments_result)):
                ?>
                    <div class="comment">
                        <div class="comment-header">
                            <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                            <span class="comment-date"><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></span>
                        </div>
                        <!-- VULNERABILITY: XSS - comments displayed without sanitization -->
                        <div class="comment-body">
                            <?php echo $comment['comment']; ?>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <p class="no-comments">No reviews yet. Be the first to review!</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 TechNovation Solutions. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
