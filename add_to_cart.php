<?php
session_start();
include('config.php');

// Check if the product ID and quantity are provided
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $image_name = isset($_POST['image_name']) ? htmlspecialchars($_POST['image_name']) : '';

    // Validate the quantity to ensure it's a valid number and positive
    if (!is_numeric($quantity) || $quantity <= 0) {
        $_SESSION['error_message'] = "Invalid quantity.";
        header('Location: product_catalog.php');
        exit;
    }

    // Fetch product details from the database
    $query = "SELECT * FROM products WHERE product_id = '$product_id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        // Extract product details
        $price = $product['price'];
        $name = $product['name'];

        // Initialize the cart session if not already set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add product to cart or update quantity if it already exists
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            $_SESSION['cart'][$product_id]['total_price'] = $_SESSION['cart'][$product_id]['quantity'] * $price;
        } else {
            $_SESSION['cart'][$product_id] = [
                'product_id' => $product_id,
                'name' => $name,
                'price' => $price,
                'image_name' => $image_name,
                'quantity' => $quantity,
                'total_price' => $quantity * $price
            ];
        }

        header('Location: product_catalog.php');
        exit;
    } else {
        $_SESSION['error_message'] = "Product not found.";
        header('Location: product_catalog.php');
        exit;
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header('Location: product_catalog.php');
    exit;
}
?>