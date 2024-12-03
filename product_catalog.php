<?php
session_start();
$title = "Product Catalog";
include('config.php');
include('dependencies/header.php');

// Set the number of products per page
$limit = 3;

// Get the current page from URL, default is 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Check if a search term or category is provided
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare query to fetch products with pagination based on search and category
$query = "SELECT * FROM products WHERE name LIKE ? AND category LIKE ? LIMIT ? OFFSET ?";

// Query to fetch distinct categories from the products table
$category_query = "SELECT DISTINCT category FROM products";
$category_result = mysqli_query($conn, $category_query);

$categories = [];
if ($category_result && mysqli_num_rows($category_result) > 0) {
    while ($cat_row = mysqli_fetch_assoc($category_result)) {
        $categories[] = $cat_row['category'];
    }
}

// Prepare statement to prevent SQL injection
$stmt = mysqli_prepare($conn, $query);
$searchTerm = "%$search%";
$categoryTerm = "%$category%";
mysqli_stmt_bind_param($stmt, "ssii", $searchTerm, $categoryTerm, $limit, $offset);

// Execute the query
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Get total number of products for pagination
$total_query = "SELECT COUNT(*) AS total FROM products WHERE name LIKE ? AND category LIKE ?";
$total_stmt = mysqli_prepare($conn, $total_query);
mysqli_stmt_bind_param($total_stmt, "ss", $searchTerm, $categoryTerm);
mysqli_stmt_execute($total_stmt);
$total_result = mysqli_stmt_get_result($total_stmt);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);
?>

<style>
    <?php include('css/product_catalog.css'); ?>
</style>
<div class="container">
    <h3>Product Catalog</h3>

    <!-- Cart Link -->
    <div class="cart-link">
        <a href="view_cart.php" class="btn-link">View Cart</a>
        <a href="main_menu.php" class="btn-link">Return to Main Menu</a>
    </div>

    <!-- Search and Category Filter Form -->
    <div class="search-filter-container">
        <form method="GET" action="product_catalog.php">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
            
            <!-- Category filter dropdown -->
            <select name="category">
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php if ($category == $cat) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <input type="submit" value="Filter">
        </form>
    </div>

    <!-- Display Products -->
    <div class="product-list">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Construct the image path
                $image_name = isset($row['image_name']) ? $row['image_name'] : '';
                $image_url = !empty($image_name) && file_exists("images/$image_name") 
                    ? "images/" . htmlspecialchars($image_name) 
                    : 'images/default_image.png';
                
                echo "<div class='product-card'>
                    <img src='" . $image_url . "' class='product-image'>
                    <div class='product-info'>
                        <h2 class='product-name'>" . htmlspecialchars($row['name']) . "</h2>
                        <p class='product-price'>$" . number_format($row['price'], 2) . "</p>
                    </div>
                    <p>" . htmlspecialchars($row['description']) . "</p>

                    <!-- Add to Cart Button -->
                    <form action='add_to_cart.php' method='POST' onsubmit='return confirmStockAlert(this)'>
                        <input type='hidden' name='product_name' value='" . htmlspecialchars($row['name']) . "'>
                        <input type='hidden' name='product_id' value='" . $row['product_id'] . "'>
                        <input type='hidden' name='price' value='" . $row['price'] . "'>
                        <input type='hidden' name='image_name' value='" . htmlspecialchars($row['image_name']) . "'>
                        <input type='hidden' name='quantity' value='1'>
                        <input type='submit' value='Add to Cart' class='add-to-cart'>
                    </form>
                </div>";
            }
        } else {
            echo "<p>No products found.</p>";
        }
        ?>
    </div>

    <!-- Pagination Links -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="product_catalog.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="product_catalog.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="product_catalog.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>&copy; 2024 goNuts - All Rights Reserved. Crafted with love for health and wellness.</p>
</footer>

<?php
// Close database connection
mysqli_stmt_close($stmt);
mysqli_stmt_close($total_stmt);
mysqli_close($conn);
?>

<script>
    function confirmStockAlert(form) {
        const productId = form.product_id.value;
        const productName = form.product_name.value;
        const quantity = parseInt(form.quantity.value);
        const price = parseFloat(form.price.value);
        const totalPrice = price * quantity;

        alert(
            "Product Name: " + productName + 
            "\nProduct ID: " + productId + 
            "\nQuantity: " + quantity + 
            "\nTotal Price: $" + totalPrice.toFixed(2)
        );

        return true;
    }
</script>
</body>
</html>