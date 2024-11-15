<?php
include_once "db_connection.php";


session_start();
$conn = new mysqli('localhost', 'root', '', 'student_rewards'); // Replace 'mysql' with the service name if you're in Docker

// Check if the user is logged in as admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_rewards');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve post data by ID
if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);
    $post_sql = "SELECT * FROM posts WHERE id = ?";
    $stmt_post = $conn->prepare($post_sql);
    $stmt_post->bind_param("i", $post_id);
    $stmt_post->execute();
    $post_result = $stmt_post->get_result();
    
    if ($post_result->num_rows === 0) {
        echo "<p>Post not found!</p>";
        exit();
    }

    $post = $post_result->fetch_assoc();
} else {
    echo "<p>No post ID provided!</p>";
    exit();
}

// Handle form submission to update post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $post['image']; // Retain old image if no new image is uploaded
    
    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";  // Folder where images will be uploaded
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if file is an image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "<p>File is not an image.</p>";
            exit();
        }
        
        // Allow only certain image formats
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "<p>Only JPG, JPEG, PNG & GIF files are allowed.</p>";
            exit();
        }
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file; // Update the image path
        } else {
            echo "<p>Error uploading image.</p>";
            exit();
        }
    }

    // Update the post in the database
    $update_sql = "UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("sssi", $title, $content, $image, $post_id);
    
    if ($stmt_update->execute()) {
        echo "<p>Post updated successfully.</p>";
        header("Location: manage_posts.php");
        exit();
    } else {
        echo "<p>Error updating post: " . $conn->error . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="adminstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header">
        <h2>Edit Post</h2>
     

        <button onclick="location.href='logout.php'" class="logout-button">Log Out</button>
    </div>

    <div class="sidebar">
        <h2>Manage Posts</h2>
        <ul>
            <li><a href="admindash.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="admin.php"><i class="fas fa-users"></i> Manage Students</a></li>
            <li><a href="manage_posts.php"><i class="fas fa-edit"></i> Manage Posts</a></li> <!-- New link -->
            <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
            
        </ul>
    </div>

    <div class="main-content">
   
        <h3>Edit Post</h3>
        <form action="edit_post.php?id=<?php echo $post['id']; ?>" method="POST" enctype="multipart/form-data">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            
            <label for="content">Content</label>
            <textarea id="content" name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            
            <label for="image">Image (Leave blank to keep existing image)</label>
            <input type="file" id="image" name="image" accept="image/*">
            
            <?php if (!empty($post['image'])): ?>
                <p>Current image: <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" width="100"></p>
            <?php endif; ?>
            
            <button type="submit">Update Post</button>
        </form>
    </div>
</body>
</html>
