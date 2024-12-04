<?php
session_start(); // Start the session at the top of the page

$title = "Account Login";
include('dependencies/header.php');
include('config.php'); // Include your database connection file

// Handle the login process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form input and escape for security
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Query the database to check for matching username and password
    $query = "SELECT * FROM accounts WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // User found, fetch user details and set session variables
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['account_id']; // Set the user_id session variable
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['mobile_num'] = $user['mobile_num'];

        // Redirect to the main menu page after successful login
        header("Location: main_menu.php");
        exit(); // Make sure the script stops after redirecting
    } else {
        // Invalid login details
        echo "<script>alert('Invalid username or password!');</script>";
    }

    // Free the result and close the database connection
    mysqli_free_result($result);
}

mysqli_close($conn); // Close the database connection
?>

<!-- HTML Form for login -->
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

<style>
    <?php include('css/account_login.css'); ?>
</style>
</body>
</html>
