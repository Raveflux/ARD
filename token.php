<?php
include_once "db_connection.php";
// Step 1: Connect to the database
$conn = new mysqli('localhost', 'root', '', 'student_rewards');
 // Replace 'mysql' with the service name if you're in Docker

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Generate a unique token using random_bytes
$token = bin2hex(random_bytes(50)); // 100-character token

// Step 3: Set the token expiration time (1 hour)
$expires = time() + 3600; // Token expires in 1 hour

// Step 4: Assume you have the student's ID (e.g., $student_id)
$student_id = 1; // Example student_id, change as needed

// Step 5: Insert the token and expiration time into the database
$sql = "INSERT INTO password_resets (student_id, token, expires) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('isi', $student_id, $token, $expires);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Token has been generated and stored successfully!";
} else {
    echo "Error storing token.";
}

// Close connection
$stmt->close();
$conn->close();
?>
