<?php
// Fetch database credentials from environment variables with descriptive names
$db_host = getenv('DB_HOST') ?: 'localhost';       // Database host (e.g., localhost or the Coolify server)
$db_user = getenv('DB_USERNAME') ?: 'root';        // Database username
$db_pass = getenv('DB_PASSWORD') ?: '';            // Database password
$db_name = getenv('DB_DATABASE') ?: 'student_rewards'; // Database name
$db_port = getenv('DB_PORT') ?: '3306';            // Database port, default to 3306 if not set

// Establish a connection to the database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

session_start();
?>
