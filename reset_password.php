<?php
// Ensure the user can't access this page without a valid token
if (!isset($_GET['token'])) {
    die("Invalid token.");
}

$token = $_GET['token'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_rewards');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if token exists and is valid
$sql = "SELECT r.student_id, r.expires, s.email FROM password_resets r JOIN students s ON r.student_id = s.id WHERE r.token = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $expires = $row['expires'];
    $email = $row['email'];

    // Check if the token has expired
    if (time() > $expires) {
        echo "Your token has expired. Please request a new one.";
        exit;
    }
} else {
    echo "Invalid token.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['password'];

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $sql = "UPDATE students SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $hashed_password, $email);
    if ($stmt->execute()) {
        // Remove the reset token after successful password update
        $sql = "DELETE FROM password_resets WHERE token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $token);
        $stmt->execute();

        echo "Your password has been reset successfully!";
    } else {
        echo "Error resetting your password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Your Password</h2>
    <form method="POST">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
