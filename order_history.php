<?php
session_start();
include('config.php');

// Ensure the user is logged in
if (!isset($_SESSION['account_id']) || !isset($_SESSION['email'])) {
    header("Location: account_login.php"); // Redirect to login page if not logged in
    exit();
}

// Retrieve user details from session
$account_id = $_SESSION['account_id'];
$email = $_SESSION['email'];

// Fetch order history from the database
$sql = "SELECT order_id, customer_name, phone, email, total_price, payment_method, order_date 
        FROM orders 
        WHERE email = ? 
        ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>

    <style>
        <?php include('css/order_history.css'); ?>
    </style> 
</head>
<body>
<div class="container">
    <h1 class="order-history-title">Order History</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <table class="order-table" border="1">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['order_id']) ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>$<?= number_format($row['total_price'], 2) ?></td>
                        <td><?= htmlspecialchars($row['payment_method']) ?></td>
                        <td><?= htmlspecialchars($row['order_date']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no previous transactions.</p>
    <?php endif; ?>

    <div class="order-actions">
        <a href="main_menu.php" class="btn-back">Return to Main Menu</a>
    </div>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>