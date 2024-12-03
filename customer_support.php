<?php
session_start();
$title = "Customer Support";
include('config.php');
include('dependencies/header.php');

// Handle form submission
$success_message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);
    
    // Insert into the `tickets` table
    $sql = "INSERT INTO tickets (Name, Email, Subject, Message) VALUES ('$name', '$email', '$subject', '$message')";
    if ($conn->query($sql) === TRUE) {
        $ticket_id = $conn->insert_id;
        $success_message = "Your concern has been submitted successfully! Your Ticket ID is: " . $ticket_id;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<style>
    <?php include('css/customer_support.css'); ?>
</style>

<div class="container">
    <h1>Customer Support</h1>
    <?php if (!empty($success_message)): ?>
        <div class="success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" 
            value="<?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" 
            value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>

        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <button type="submit" class="btn-submit">Submit</button>
    </form>
    <a href="main_menu.php" class="btn-back">Return to Main Menu</a>
</div>
</body>
</html>
