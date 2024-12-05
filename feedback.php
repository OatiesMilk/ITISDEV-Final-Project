<?php
session_start();
include('config.php');
include('dependencies/header.php');

$title = "Feedback Page";

// Handle feedback submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Get user ID from session
        $feedback_message = $conn->real_escape_string($_POST['feedback_message']);

        // Insert feedback into database
        $sql = "INSERT INTO feedback (user_id, feedback_message) VALUES ('$user_id', '$feedback_message')";
        
        if ($conn->query($sql) === TRUE) {
            $success_message = "Thank you for your feedback!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    } else {
        $error_message = "You must be logged in to submit feedback.";
    }
}

// Fetch existing feedbacks (optional)
$sql = "SELECT f.feedback_message, f.created_at, a.firstname, a.lastname
        FROM feedback f
        JOIN accounts a ON f.user_id = a.account_id
        ORDER BY f.created_at DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/feedback.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center text-uppercase text-primary mb-4">Submit Your Feedback</h1>

    <!-- Display success or error messages -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Go back to main menu button -->
    <div class="mb-5">
        <a href="main_menu.php" class="btn btn-primary">Go Back to Main Menu</a>
    </div>

    <!-- Feedback form -->
    <div class="feedback-form mb-5">
        <h2 class="h4">We value your feedback!</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="feedback_message" class="form-label">Your Feedback</label>
                <textarea id="feedback_message" name="feedback_message" class="form-control" rows="5" placeholder="Tell us what you think!" required></textarea>
            </div>

            <button type="submit" name="submit_feedback" class="btn btn-primary btn-lg">Submit Feedback</button>
        </form>
    </div>

    <!-- Display all feedback -->
    <div class="feedback-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="feedback-item mb-4 p-4 border rounded shadow-sm">
                    <p class="text-muted small">Feedback by <?php echo htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']); ?> on <?php echo $row['created_at']; ?></p>
                    <p><?php echo nl2br(htmlspecialchars($row['feedback_message'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No feedback available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>