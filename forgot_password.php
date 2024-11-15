<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'mail.php'; // Include PHPMailer setup and sendEmail function

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'student_rewards');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email exists
    $sql = "SELECT id FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $student_id = $row['id'];

        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(50)); // 100-character token
        $expires = date('U') + 3600; // Token valid for 1 hour

        // Save token and expiry to the database
        $sql = "INSERT INTO password_resets (student_id, token, expires) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isi', $student_id, $token, $expires);
        $stmt->execute();

        // Create the reset link
        $reset_link = "http://yourdomain.com/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $body = "Please click the following link to reset your password: $reset_link";

        // Use PHPMailer to send the email (via mail.php)
        $mail_sent = sendEmail($email, $subject, $body); // Correct function call

        if ($mail_sent) {
            $success_message = "A password reset link has been sent to your email address.";
        } else {
            $error_message = "";
        }
    } else {
        $error_message = "No account found with that email address.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="regstyles.css">
</head>
<body>
    <h2>Forgot Password</h2>
    <br>
    <?php if (!empty($error_message)) : ?>
        <p class="error-text"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if (!empty($success_message)) : ?>
        <p class="success-text"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <form method="post" action="forgot_password.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
