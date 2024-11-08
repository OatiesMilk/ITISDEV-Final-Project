<?php
    $title = "Main Menu";
    include('dependencies/header.php');
?>

<style>
    <?php include('css/footer.css');?>
    <?php include('css/main_menu.css');?>
</style>

<!-- content -->
<h1>Main Menu</h1>

<?php 
    echo "<script>alert('Login successful! Welcome, $username');</script>"; 
?>
<!-- end of content -->

<?php
    include('dependencies/footer.php');
?>