<?php
session_start();
$title = "Your Shopping Cart";
include('config.php');
include('dependencies/header.php');

// Handle updates or item removal from the cart
if (isset($_GET['action']) && isset($_GET['id'])) {
    // Sanitize product ID from GET
    $product_id = (int)$_GET['id'];

    if ($_GET['action'] == 'remove') {
        // Remove product from the cart
        unset($_SESSION['cart'][$product_id]);
    } elseif ($_GET['action'] == 'update' && isset($_GET['quantity'])) {
        // Sanitize and validate quantity
        $quantity = (int)$_GET['quantity'];
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } else {
            // If quantity is invalid, remove the product
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

// Ensure that $_SESSION['cart'] is an array
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculate total price
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        // Ensure $item is an array and has necessary keys
        if (is_array($item) && isset($item['price']) && isset($item['quantity'])) {
            $total_price += $item['price'] * $item['quantity'];
        }
    }
}
?>

<style>
    <?php include('css/cart.css'); ?>
</style>

    <h1>Your Shopping Cart</h1>

    <!-- Debugging: Check the session contents -->
    <pre>
    <?php
    // Uncomment for debugging if needed
    // var_dump($_SESSION['cart']);
    ?>
    </pre>

    <!-- Display Cart Contents -->
    <?php if (!empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                    <?php if (is_array($item)): ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-image"></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>
                                <form action="view_cart.php" method="GET">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?= $product_id ?>">
                                    <input type="submit" value="Update">
                                </form>
                            </td>
                            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            <td>
                                <a href="view_cart.php?action=remove&id=<?= $product_id ?>">Remove</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Total: $<?= number_format($total_price, 2) ?></h3>

        <a href="checkout.php">Proceed to Checkout</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <a href="product_catalog.php">Continue Shopping</a>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
