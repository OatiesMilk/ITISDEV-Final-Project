<?php
session_start();
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="styles/product_detail.css"> <!-- Link to your CSS -->
</head>
<body>

    <h1>Product Details</h1>

    <div class="product-detail">
        <div class="product-image">
            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
        </div>
        
        <div class="product-info">
            <h2><?php echo $product['name']; ?></h2>
            <p><strong>Category:</strong> <?php echo $product['category']; ?></p>
            <p><strong>Description:</strong> <?php echo nl2br($product['description']); ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
            <p><strong>Stock:</strong> <?php echo $product['stock']; ?> left</p>
            
            <!-- Add to Cart Button (Optional) -->
            <form method="POST" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" required>
                <input type="submit" value="Add to Cart">
            </form>
        </div>
    </div>

    <?php
    mysqli_close($conn);
    include('dependencies/footer.php');
    ?>

</body>
</html>
