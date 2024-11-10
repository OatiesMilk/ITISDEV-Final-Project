<?php
    // Start the session only if it hasn't already been started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if the session has the 'username' set
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        $username = 'Guest';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Default Title'; ?></title>
</head>
<body>
    <!-- The body of your page goes here -->
</body>
</html>
