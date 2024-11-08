<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        <?php include('css/footer.css'); ?>
        <?php include('css/account_login.css'); ?>
    </style>
</head>
<body>

<?php
    include('config.php');
    include('dependencies/header.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        
        // Query the database for the username and password
        $query = "SELECT * FROM accounts WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['firstname'] = $user['firstname']; // Getting the firstname of the account matched

            echo "<script>alert('Login successful. Welcome, " . $user['firstname'] . "!');</script>";
            
            header("Location: main_menu.php");
        } else {
            echo "<script>alert('Invalid username or password!');</script>";
        }

        mysqli_free_result($result);
    }

    mysqli_close($conn);
?>


<div class="container">
    <h2>Login Account</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="login_form">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>