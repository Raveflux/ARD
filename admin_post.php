<?php
include_once "db_connection.php";

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
$conn = new mysqli('localhost', 'root', '', 'student_rewards'); // Replace 'mysql' with the service name if you're in Docker

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $posted_by = 'admin'; // or use $_SESSION['admin_username']

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'student_rewards');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO posts (title, content, posted_by) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $title, $content, $posted_by);

    if ($stmt->execute()) {
        header("Location: admin.php"); // Redirect after success
        exit();
    } else {
        $error_message = "Error posting the content.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Content</title>
</head>
<body>
    <h2>Create a Post</h2>
    <?php if (!empty($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="content">Content:</label>
        <textarea id="content" name="content" required></textarea><br>

        <button type="submit">Post</button>
    </form>
</body>
</html>
