<?php
session_start();
include('config.php');

date_default_timezone_set('Asia/Manila');

// Check if the cart is not empty
if (!empty($_SESSION['cart'])) {
    // Retrieve customer information from session
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
    $email = $_SESSION['email'];
    $mobile_num = $_SESSION['mobile_num'];
    
    // Compute total price
    $total_price = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }

    // Set static payment method for now
    $payment_method = "Cash";

    // Prepare customer name
    $customer_name = $firstname . ' ' . $lastname;

    // Get current date and time
    $order_date = date('Y-m-d H:i:s');

    // Insert order into the database
    $sql = "INSERT INTO orders (customer_name, phone, email, total_price, payment_method, order_date) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $customer_name, $mobile_num, $email, $total_price, $payment_method, $order_date);

    if ($stmt->execute()) {
        // Clear the cart after successful order
        unset($_SESSION['cart']);
        header("Location: thank_you.php");
        exit();
    } else {
        // Handle database errors
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Redirect back to the checkout page if the cart is empty
    header("Location: checkout.php");
    exit();
}

$conn->close();
?>