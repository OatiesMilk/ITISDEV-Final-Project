<?php
session_start();
include('config.php');

// Proceed with checkout logic
// Here, you would typically process payment, shipping, and finalize the order.

// Initialize total price
$total_price = 0;

// Calculate total price if the cart is not empty
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>

    <!-- Display Cart Contents for Review -->
    <?php if (!empty($_SESSION['cart'])): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td><img src="" alt="Product Image" class="product-image"></td>
                        <td><?= $item['name'] ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Total: $<?= number_format($total_price, 2) ?></h3>

        <form method="POST" action="process_checkout.php">
            <!-- Here you would collect payment information -->
            <input type="submit" value="Complete Purchase">
        </form>
        <a href="main_menu.php" class="btn">Go to Main Menu</a>
    <?php else: ?>
        <p>Your cart is empty. Add items to your cart before proceeding to checkout.</p>
    <?php endif; ?>
</body>
</html>