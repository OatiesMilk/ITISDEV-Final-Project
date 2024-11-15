<?php
session_start();
include('config.php');
include('dependencies/header.php');

// Set the number of products per page
$limit = 10;

// Get the current page from URL, default is 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Check if a search term or category is provided
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare query to fetch products with pagination based on search and category
$query = "SELECT * FROM products WHERE name LIKE ? AND category LIKE ? LIMIT ? OFFSET ?";

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <link rel="stylesheet" href="styles/product_catalog.css"> <!-- Link to your CSS -->
</head>
<body>

    <h1>Product Catalog</h1>

    <!-- Search and Category Filter Form -->
    <form method="GET" action="product_catalog.php">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
        
        <!-- Category filter dropdown -->
        <select name="category">
            <option value="">Select Category</option>
            <option value="Nuts" <?php if ($category == 'Nuts') echo 'selected'; ?>>Nuts</option>
            <option value="Dried Fruits" <?php if ($category == 'Dried Fruits') echo 'selected'; ?>>Dried Fruits</option>
            <option value="Seeds" <?php if ($category == 'Seeds') echo 'selected'; ?>>Seeds</option>
        </select>
        
        <input type="submit" value="Filter">
    </form>

    <!-- Display Products -->
    <div class="product-list">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Ensure that the 'image_url' field exists in the database for the product
                $image_url = isset($row['image_url']) ? $row['image_url'] : 'default_image.jpg'; // Fallback image
                
                echo "<div class='product-card'>
                        <img src='" . htmlspecialchars($image_url) . "' alt='" . htmlspecialchars($row['name']) . "' class='product-image'>
                        <h2>" . htmlspecialchars($row['name']) . "</h2>
                        <p>" . htmlspecialchars(substr($row['description'], 0, 100)) . "...</p>
                        <p>$" . number_format($row['price'], 2) . "</p>
                        <a href='product_detail.php?id=" . $row['product_id'] . "' class='view-details'>View Details</a>
                        
                        <!-- Add to Cart Button -->
                        <form action='add_to_cart.php' method='POST'>
                            <input type='hidden' name='product_id' value='" . $row['product_id'] . "'>
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

    <!-- Cart Link -->
    <div class="cart-link">
        <a href="view_cart.php">View Cart</a>
    </div>

    <?php
    // Close database connection
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($total_stmt);
    mysqli_close($conn);
    include('dependencies/footer.php');
    ?>

</body>
</html>
