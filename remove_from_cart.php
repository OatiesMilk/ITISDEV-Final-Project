<?php
session_start();
include('config.php');

// Check if product ID is set
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    // Remove product from cart
    unset($_SESSION['cart'][$product_id]);
}

// Redirect back to the cart page
header('Location: cart.php');
?>
