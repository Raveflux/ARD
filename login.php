<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'student_rewards'); // Replace 'mysql' with the service name if you're in Docker

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the login button was clicked
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, password, status FROM students WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($row['status'] !== 'approved') {
                $error_message = "Account pending approval. Please wait for admin approval.";
            } elseif (password_verify($password, $row['password'])) {
                $_SESSION['student_id'] = $row['id'];
                header("Location: student.php");
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Invalid username or password.";
        }

        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <script>
        // JavaScript function to redirect to register page
        function redirectToRegister() {
            window.location.href = "register.php";
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="leftdiv">
            <img src="wifi.jpg" alt="Description of the image">
        </div>
        <div class="login-container">
            <h2>Login</h2>
            <br>
            <?php if (!empty($error_message)) : ?>
                <p class="error-text"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="post" action="login.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>

                <!-- Login button -->
                <button type="submit" name="login">Login</button>
                
                <!-- Register button -->
                <button type="button" class="register-btn" onclick="redirectToRegister()">Register</button>
            </form>
            <br>
            <a href="forgot_password.php">Forgot Password?</a> <br>
            <a href="admin_login.php">Admin Login</a> <!-- Link to admin login -->
        </div>
    </div>
</body>
</html>
