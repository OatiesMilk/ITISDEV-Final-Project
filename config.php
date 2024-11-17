<?php
$database = "itisdev";
$localhost = "localhost";
$conn = mysqli_connect($localhost, "root", "p@ssword", $database); // Empty password
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Ensure UTF-8 encoding
$conn->set_charset("utf8mb4");
?>
