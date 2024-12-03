<?php
session_start();
$title = "Product Details";
include('config.php');
include('dependencies/header.php');



// Check if product ID is passed via URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // Query to fetch the product details
    $query = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);
    
    // Check if product exists
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "<p>Product not found.</p>";
        exit;
    }
} else {
    echo "<p>No product selected.</p>";
    exit;
}
?>

<style>
    <?php 
        include('css/product_detail.css');
        include('css/back_button.css'); 
    ?>
</style>
<div class="container">

    <h1>Product Details</h1>

    <div class="product-detail">
        <div class="product-image">
            <!-- <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>"> -->
        </div>
        
        <div class="product-info">
            <p><strong><?php echo $product['name']; ?></strong></p>
            <p><strong>Category:</strong> <?php echo $product['category']; ?></p>
            <p><strong>Description:</strong> <?php echo nl2br($product['description']); ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
            <p><strong>Stock:</strong> <?php echo $product['stock']; ?> left</p>
            
            <!-- Add to Cart Button -->
            <form method="POST" action="add_to_cart.php" onsubmit="return confirmStockAlert(this)">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <input type="hidden" name="price" value="<?php echo $product['price']; ?>" required>
                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" required>
                <input type="submit" value="Add to Cart">
            </form>
        </div>
    </div>

    <!-- Back Button -->
    <div class="back-button">
        <button onclick="history.back()">Back</button>
    </div>
</div>

    <?php
    mysqli_close($conn);
    ?>

</body>

<script>
    function confirmStockAlert(form) {
        // Get the product ID and quantity
        const productId = form.product_id.value;
        const quantity = parseInt(form.quantity.value); // Convert to an integer
        const price = parseFloat(form.price.value); // Convert to a floating-point number
        const totalPrice = price * quantity; // Calculate total price

        // Show the alert with product ID, quantity, and total price
        alert("Product ID: " + productId + "\nQuantity: " + quantity + "\nTotal Price: $" + totalPrice.toFixed(2));

        // Return true to allow the form submission
        return true;
    }
</script>

</html>

