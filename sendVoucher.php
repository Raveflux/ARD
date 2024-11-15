<?php
include_once "db_connection.php";

require 'mail.php'; // Include your mail setup
$conn = new mysqli('localhost', 'root', '', 'student_rewards'); 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voucher_code = $_POST['voucher_code'];
    $recipient_email = $_POST['recipient_email'];

    // Fetch the voucher details from the database
    $voucher_query = "SELECT duration, duration_unit FROM vouchers WHERE voucher_code = ?";
    $stmt = $conn->prepare($voucher_query);
    $stmt->bind_param("s", $voucher_code);
    $stmt->execute();
    $stmt->bind_result($duration, $duration_unit);
    $stmt->fetch();
    $stmt->close();

    if ($duration && $duration_unit) {
        // Define subject and message content including the duration
        $subject = "Your Voucher Code";
        $body = "Here is your voucher code: $voucher_code. Time Duration $duration $duration_unit.";

        // Send the email
        sendEmail($recipient_email, $subject, $body);

        // Redirect back with a success message
        header("Location: admin_voucher.php?status=sent");
        exit();
    } else {
        echo "Invalid voucher code.";
    }
}
?>
