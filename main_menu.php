<?php
    session_start();
    $title = "Main Menu";
    include('dependencies/header.php');
?>

<style>
    <?php include('css/footer.css'); ?>
    <?php include('css/main_menu.css'); ?>
</style>

<!-- Main Menu Content -->
<h1>Welcome to goNuts!</h1>
<p>Your Health and Wellness Shopping Experience</p>

<div class="menu-options">
    <a href="product_catalog.php" class="menu-item">Product Catalog</a>
    <a href="order_history.php" class="menu-item">Order History</a>
    <a href="customer_support.php" class="menu-item">Customer Support</a>
    <a href="community.php" class="menu-item">Community</a>
    <a href="feedback.php" class="menu-item">Feedback</a>
</div>

<?php
    // Display welcome message
    if (isset($_SESSION['firstname'])) {
        echo "<script>alert('Login successful. Welcome, " . $_SESSION['firstname'] . "!');</script>";
    }
?>

<!-- End of Main Menu -->

<?php
    include('dependencies/footer.php');
?>
