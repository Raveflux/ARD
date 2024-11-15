<?php
include_once "db_connection.php";
// Set up the connection to the database
$conn = new mysqli('localhost', 'root', '', 'student_rewards');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the top 3 students of the month based on points
$sql = "SELECT id, name, points, profile_picture, email FROM students ORDER BY points DESC LIMIT 3";
$result = $conn->query($sql);

$top_students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $top_students[] = $row;
    }
}

// Fetch unredeemed reward codes from the rewards_codes table
$sql = "SELECT id, code FROM rewards_codes WHERE is_redeemed = 0 LIMIT 3";
$voucher_result = $conn->query($sql);

$voucher_codes = [];
if ($voucher_result->num_rows > 0) {
    while ($voucher = $voucher_result->fetch_assoc()) {
        $voucher_codes[] = $voucher;
    }
}

// Check if there are enough unredeemed codes (3 codes)
if (count($voucher_codes) == 3) {
    // Assign vouchers to the top 3 students and mark the codes as redeemed
    for ($i = 0; $i < 3; $i++) {
        $student = $top_students[$i];
        $voucher = $voucher_codes[$i];

        // Insert the reward into the rewards table
        $insert_reward_sql = "INSERT INTO rewards (student_id, voucher_code) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_reward_sql);
        $stmt->bind_param("is", $student['id'], $voucher['code']);
        $stmt->execute();

        // Update the reward code as redeemed
        $update_code_sql = "UPDATE rewards_codes SET is_redeemed = 1 WHERE id = ?";
        $stmt = $conn->prepare($update_code_sql);
        $stmt->bind_param("i", $voucher['id']);
        $stmt->execute();

        // Send email to the student about the reward
        $to = $student['email'];
        $subject = "Congratulations! You've received a voucher!";
        $message = "
            <html>
            <head>
                <title>Congratulations!</title>
            </head>
            <body>
                <p>Dear " . htmlspecialchars($student['name']) . ",</p>
                <p>Congratulations on being one of the top 3 students this month! You've earned a voucher for your achievements:</p>
                <p><strong>Voucher Code: " . $voucher['code'] . "</strong></p>
                <p>Keep up the great work!</p>
                <p>Best Regards, <br>Your Rewards Program Team</p>
            </body>
            </html>
        ";

        // To send HTML mail, the Content-type header must be set
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: rewards@yourdomain.com" . "\r\n";  // Make sure this is a valid sender email

        // Send email
        mail($to, $subject, $message, $headers);
    }
} else {
    echo "Not enough unredeemed voucher codes available!";
}

$conn->close();
?>
