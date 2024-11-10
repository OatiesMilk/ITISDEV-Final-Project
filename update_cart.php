<?php
session_start();
include('config.php');

// Check if product ID and quantity are set in the POST request
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    // Sanitize input by casting product ID and quantity to integers
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    // Validate that the quantity is a positive integer
    if ($quantity > 0) {
        // Add or update product in the cart
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        // If quantity is zero or negative, remove product from the cart
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
} else {
    // Redirect with an error if product_id or quantity is missing
    $_SESSION['error_message'] = "Product ID or quantity is missing.";
}

// Redirect back to the cart page
header('Location: cart.php');
exit;
?>
