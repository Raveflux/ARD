<?php
include_once "db_connection.php";

session_start();
$conn = new mysqli('localhost', 'root', '', 'student_rewards'); // Replace 'mysql' with the service name if you're in Docker

// Constant credentials
define('ADMIN_EMAIL', 'admin');
define('ADMIN_PASSWORD', 'admin2580');

// Check if user is logged in as admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

$message = "";

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password
    if ($current_password !== ADMIN_PASSWORD) {
        $message = "Current password is incorrect!";
    } elseif ($new_password !== $confirm_password) {
        $message = "New passwords do not match!";
    } else {
        // Here, set the session variable or a constant to the new password
        // In practice, update the password securely if using a database
        // Example of setting a session variable:
        $_SESSION['admin_password'] = $new_password;
        
        $message = "Password changed successfully! (Note: In a real application, this would persist in a database)";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Change Password</title>
    <link rel="stylesheet" href="adminstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="header"> 
<button onclick="location.href='logout.php'" class="logout-button">Log Out</button>
</div>
<div class="sidebar">
    <h2>Manage Students</h2>
    <ul>
        <li><a href="admindash.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="admin.php"><i class="fas fa-users"></i> Manage Students</a></li>
        <li><a href="manage_posts.php"><i class="fas fa-edit"></i> Manage Posts</a></li>
        <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
        <li><a href="adminsettings.php"><i class="fas fa-cog"></i> Settings</a></li>
    </ul>
</div>

<div class="main-content">
    <h2>Change Password</h2>
    
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" action="adminsettings.php">
        <label for="current_password">Current Password</label>
        <input type="password" name="current_password" required>

        <label for="new_password">New Password</label>
        <input type="password" name="new_password" required>

        <label for="confirm_password">Confirm New Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Change Password</button>
    </form>
</div>
</body>
</html>
