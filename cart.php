<?php
session_start();
include('config.php');

// Check if product ID and quantity are provided
if (isset($_GET['id']) && isset($_GET['quantity'])) {
    $product_id = (int)$_GET['id'];  // Sanitize input by casting to an integer
    $quantity = (int)$_GET['quantity'];  // Sanitize quantity to ensure it's an integer

    // Validate quantity to make sure it's positive
    if ($quantity <= 0) {
        // Redirect back with an error if quantity is invalid
        $_SESSION['error_message'] = "Invalid quantity.";
        header('Location: product_catalog.php');
        exit;
    }

    // Fetch product details from the database
    $query = "SELECT * FROM products WHERE product_id = $product_id LIMIT 1";  // Limit to one result
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        // Prepare product data to add to the cart
        $product = [
            'id' => $row['product_id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $quantity,
            'image_url' => $row['image_url']
        ];

        // Check if the product is already in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            // Update quantity if product already exists in the cart
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Add new product to the cart
            $_SESSION['cart'][$product_id] = $product;
        }
    } else {
        // If the product is not found in the database, set an error message
        $_SESSION['error_message'] = "Product not found.";
    }
} else {
    // If product ID or quantity is missing, set an error message
    $_SESSION['error_message'] = "Invalid request. Product ID or quantity missing.";
}

// Redirect to the cart page or catalog with an error message
header('Location: view_cart.php');
exit;
?>
