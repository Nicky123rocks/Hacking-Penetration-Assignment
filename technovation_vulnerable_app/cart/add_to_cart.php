<?php
/**
 * Add to Cart Functionality
 * VULNERABILITIES: Price Manipulation, No CSRF Protection, Mass Assignment
 */
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

// VULNERABILITY: Price manipulation - trusting client-side data
if (isset($_POST['product']) && isset($_POST['price'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product'];
    $price = $_POST['price']; // Price from client - can be manipulated!
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
    
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Add item to cart
    $cart_item = array(
        'product_id' => $product_id,
        'product' => $product_name,
        'price' => $price,
        'quantity' => $quantity,
        'total' => $price * $quantity
    );
    
    // Check if product already in cart
    $found = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $_SESSION['cart'][$key]['quantity'] += $quantity;
            $_SESSION['cart'][$key]['total'] = $_SESSION['cart'][$key]['price'] * $_SESSION['cart'][$key]['quantity'];
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = $cart_item;
    }
    
    // Set session variables for quick access (for backward compatibility)
    $_SESSION['product'] = $product_name;
    $_SESSION['price'] = $price;
    
    header("Location: ../checkout.php");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
?>
