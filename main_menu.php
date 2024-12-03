<?php
    session_start();
    $title = "Main Menu";
    include('dependencies/header.php');
?>

<style>
    <?php include('css/main_menu.css'); ?>
</style>

<!-- Main Menu Content -->
<section class="banner">
    <img src="images/1.jpg" alt="GoNuts Banner" class="banner-image">
    <div class="banner-text">
        <h1>Welcome to goNuts!</h1>
        <p>Your Health and Wellness Shopping Experience</p>
        <a href="product_catalog.php" class="cta-button">Shop Now</a>
    </div>
</section>

<div class="menu-options">
    <a href="product_catalog.php" class="menu-item">Product Catalog</a>
    <a href="order_history.php" class="menu-item">Order History</a>
    <a href="customer_support.php" class="menu-item">Customer Support</a>
    <a href="community.php" class="menu-item">Community</a>
    <a href="feedback.php" class="menu-item">Feedback</a>
    <a href="account_logout.php" class="menu-item logout">Logout</a>
</div>

<footer>
    <p>&copy; 2024 goNuts - All Rights Reserved. Crafted with love for health and wellness.</p>
</footer>

<?php

?>

<!-- End of Main Menu -->

</body>
</html>