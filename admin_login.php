<?php

include_once "db_connection.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded admin credentials
    if ($username === 'admin' && $password === 'admin2580') {
        $_SESSION['admin'] = true;
        header("Location: admin.php"); // Redirect to admin page
        exit();
    } else {
        $error_message = "Invalid admin username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="login.css">
    <style>
      
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h3>Admin Login</h3>
            <?php if (!empty($error_message)) : ?>
                <p class="error-text"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="post" action="admin_login.php">
                
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>

                <button type="submit">Login</button>
            </form>
            <a href="login.php"><button class="back-button">Back to User Login</button></a> <!-- Back button -->
        </div>
    </div>
</body>
</html>
