<?php
// db_connection.php will handle the session and connection
include_once "db_connection.php";

// Check if email is provided
if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if email exists
    $sql = "SELECT id FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "exists";  // Email exists
    } else {
        echo "not_exists";  // Email does not exist
    }

    $stmt->close();
    $conn->close();
}
?>
