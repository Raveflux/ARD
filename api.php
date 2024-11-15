<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password
$dbname = "student_rewards"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// API endpoint to fetch all vouchers
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM vouchers"; // Replace with your table name
    $result = $conn->query($sql);

    $vouchers = array();
    while ($row = $result->fetch_assoc()) {
        $vouchers[] = $row;
    }

    echo json_encode($vouchers);
}

// Close connection
$conn->close();
?>
