<?php
include_once "db_connection.php";



// Check if the user is logged in as admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}



// Handle deletion of post
if (isset($_GET['delete_post'])) {
    $post_id_to_delete = intval($_GET['delete_post']);
    $delete_post_sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($delete_post_sql);
    $stmt->bind_param("i", $post_id_to_delete);
    
    if ($stmt->execute()) {
        echo "<p>Post deleted successfully.</p>";
    } else {
        echo "<p>Error deleting post: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Retrieve posts with pagination
$posts_per_page = 10;
$page_posts = isset($_GET['page_posts']) ? intval($_GET['page_posts']) : 1;
$offset_posts = ($page_posts - 1) * $posts_per_page;

$posts_sql = "SELECT id, title, content, image FROM posts LIMIT ? OFFSET ?";
$stmt_posts = $conn->prepare($posts_sql);
$stmt_posts->bind_param("ii", $posts_per_page, $offset_posts);
$stmt_posts->execute();
$posts_result = $stmt_posts->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <link rel="stylesheet" href="adminstyle.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header">
        
     
    </div>
    <div class="sidebar">
    <h2>Manage Students</h2>
    <ul>
 
        <li><a href="admin.php"><i class="fas fa-users"></i> Manage Students</a></li>
        <li><a href="admindash.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="manage_posts.php"><i class="fas fa-edit"></i> Manage Posts</a></li>
        <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
        <li><a href="admin_voucher.php"><i class="fas fa-file-alt"></i> Voucher</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
    </ul>
</div>

    <div class="main-content">
        <h2>Manage Posts</h2>
        <table class="post-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($post = $posts_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo htmlspecialchars($post['content']); ?></td>
                        <td>
                            <?php if (!empty($post['image'])): ?>
                                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" width="50">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a> |
                            <a href="manage_posts.php?delete_post=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination for Posts -->
        <div class="pagination">
            <ul>
                <?php 
                    $total_posts = $conn->query("SELECT COUNT(*) AS total FROM posts")->fetch_assoc()['total'];
                    $total_posts_pages = ceil($total_posts / $posts_per_page);
                    for ($i = 1; $i <= $total_posts_pages; $i++) {
                ?>
                    <li><a href="?page_posts=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</body>
</html>
