<?php
session_start();

$title = "Community";
include('config.php');
include('dependencies/header.php');

// Handle form submission for creating a new post
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_post'])) {
    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Get the user ID from the session
        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);

        // Insert the new post into the `community_posts` table
        $sql = "INSERT INTO community_posts (user_id, title, content) VALUES ('$user_id', '$title', '$content')";
        if ($conn->query($sql) === TRUE) {
            $success_message = "Your post has been submitted successfully!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    } else {
        $error_message = "You must be logged in to create a post.";
    }
}

// Fetch all posts from the `community_posts` table
$sql = "SELECT p.post_id, p.title, p.content, p.created_at, a.firstname, a.lastname
        FROM community_posts p
        JOIN accounts a ON p.user_id = a.account_id
        ORDER BY p.created_at DESC";
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
    <style>
        <?php include('css/community.css'); ?>
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center text-uppercase text-primary mb-4">Community Posts</h1>

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

    <!-- Form to create a new post -->
    <div class="mb-5">
        <h2>Create a New Post</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea id="content" name="content" class="form-control" rows="5" required></textarea>
            </div>

            <button type="submit" name="submit_post" class="btn btn-success">Submit Post</button>
        </form>
    </div>

    <!-- Display all posts -->
    <div class="posts-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="post mb-4 p-3 border rounded shadow-sm">
                    <h3 class="post-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p class="text-muted small">Posted by <?php echo htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']); ?> on <?php echo $row['created_at']; ?></p>
                    <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
