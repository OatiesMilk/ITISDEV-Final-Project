<?php
session_start();
include('config.php');

// Check if the product ID and quantity are provided
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Validate the quantity to make sure it's a valid number and positive
    if (!is_numeric($quantity) || $quantity <= 0) {
        // If the quantity is not valid, redirect back to the product catalog with an error message
        $_SESSION['error_message'] = "Invalid quantity.";
        header('Location: product_catalog.php');
        exit;
    }
    
    // Check if the product exists in the database
    $query = "SELECT * FROM products WHERE product_id = '$product_id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Check if the cart session exists, if not create one
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add product to cart
        if (isset($_SESSION['cart'][$product_id])) {
            // If the product already exists in the cart, update the quantity
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            // If the product is not in the cart, add it
            $_SESSION['cart'][$product_id] = $quantity;
        }

        // Redirect back to the product catalog
        header('Location: product_catalog.php');
        exit;
    } else {
        // If the product does not exist, redirect with an error message
        $_SESSION['error_message'] = "Product not found.";
        header('Location: product_catalog.php');
        exit;
    }
} else {
    // Redirect to the product catalog if no product ID or quantity is provided
    $_SESSION['error_message'] = "Invalid request.";
    header('Location: product_catalog.php');
    exit;
}
?>
