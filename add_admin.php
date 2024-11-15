<?php
// Replace these values with your own
$username = 'adminUsername';
$password = 'adminPassword'; // Plain text password

// Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_rewards');

// Check for database connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert the admin user
$sql = "INSERT INTO admins (name, username, password_hash) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $name, $username, $password_hash);

// Replace with your admin name
$name = 'Admin Name';

if ($stmt->execute()) {
    echo "Admin user added successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close database connections
$stmt->close();
$conn->close();
?>
