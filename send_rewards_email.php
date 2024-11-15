<?php
include_once "db_connection.php";
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer autoloader if using Composer

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_rewards');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve top 3 students by points
$sql = "SELECT id, name, email FROM students ORDER BY points DESC LIMIT 3";
$result = $conn->query($sql);

$topStudents = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $topStudents[] = $row;
    }
}

if (count($topStudents) < 3) {
    echo "Not enough students to send rewards.";
    exit();
}

$conn->close();

// Set up PHPMailer
$mail = new PHPMailer(true); // Enable exceptions

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'your_email@gmail.com'; // Your Gmail address
    $mail->Password   = 'your_password';        // Your Gmail password or app-specific password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Assign WiFi access durations based on rank
    $accessDurations = [
        1 => "1 month",
        2 => "2 weeks",
        3 => "1 week"
    ];

    // Send email to each top student
    foreach ($topStudents as $index => $student) {
        $rank = $index + 1;
        $accessDuration = $accessDurations[$rank];
        $studentName = htmlspecialchars($student['name']);
        $studentEmail = htmlspecialchars($student['email']);

        // Email content
        $mail->setFrom('your_email@gmail.com', 'Student Rewards Team');
        $mail->addAddress($studentEmail);
        $mail->isHTML(true);
        $mail->Subject = "Congratulations! WiFi Access Reward for Ranking #" . $rank;
        $mail->Body    = "
            <html>
            <head>
                <title>WiFi Access Reward</title>
            </head>
            <body>
                <h2>Congratulations, $studentName!</h2>
                <p>You ranked <strong>#$rank</strong> on the leaderboard and have earned <strong>$accessDuration</strong> of WiFi access.</p>
                <p>Enjoy your access!</p>
                <p>Best regards,<br>Student Rewards Team</p>
            </body>
            </html>
        ";

        // Send the email
        $mail->send();
        echo "Email sent successfully to $studentName ($studentEmail) for ranking #$rank.\n";

        // Clear all recipients for the next email
        $mail->clearAddresses();
    }

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
