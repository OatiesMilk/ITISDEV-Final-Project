<?php
session_start();
$title = "Your Shopping Cart";
include('config.php');
include('dependencies/header.php');

// Handle cart actions (update/remove items)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];

    if ($_GET['action'] == 'remove') {
        unset($_SESSION['cart'][$product_id]);
    } elseif ($_GET['action'] == 'update' && isset($_GET['quantity'])) {
        $quantity = (int)$_GET['quantity'];
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            $_SESSION['cart'][$product_id]['total_price'] = $quantity * $_SESSION['cart'][$product_id]['price'];
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

// Calculate total cart price
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        if (is_array($item) && isset($item['price'], $item['quantity'])) {
            $total_price += $item['price'] * $item['quantity'];
        }
    }
}
?>

<style>
    <?php include('css/cart.css'); ?>
</style>

<div class="container">
    <h1 class="cart-title">Your Shopping Cart</h1>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table border="1" class="cart-table">
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
                        <?php 
                        // Construct image URL
                        $image_name = isset($item['image_name']) ? $item['image_name'] : '';
                        $image_url = !empty($image_name) && file_exists("images/$image_name") 
                            ? "images/" . htmlspecialchars($image_name) 
                            : 'images/default_image.png';
                        ?>
                        <tr>
                            <td>
                                <img src="<?= $image_url ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-image">
                                <br>
                                <?= htmlspecialchars($item['name']) ?>
                            </td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>
                                <form action="view_cart.php" method="GET" class="update-form">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?= $product_id ?>">
                                    <button type="submit" class="btn-update">Update</button>
                                </form>
                            </td>
                            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            <td>
                                <a href="view_cart.php?action=remove&id=<?= $product_id ?>" class="btn-remove">Remove</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <span>Total: $<?php echo number_format($total_price, 2); ?></span> 
    <?php else: ?>
        <p class="empty-cart">Your cart is empty.</p>
    <?php endif; ?>

    <!-- Always display cart footer, even if cart is empty -->
    <div class="cart-footer">
        <div class="cart-summary">
            <?php if (!empty($_SESSION['cart'])): ?>
                <form method="POST" action="process_checkout.php" class="checkout-form">
                    <button type="submit" class="btn-checkout">Complete Purchase</button>
                </form>
            <?php else: ?>
                <span>Please add items to your cart.</span>
            <?php endif; ?>
        </div>
        <div class="cart-actions">
            <a href="product_catalog.php" class="btn-continue">Continue Shopping</a>
        </div>
    </div>
</div>
</body>
</html>

<?php
mysqli_close($conn);
?>