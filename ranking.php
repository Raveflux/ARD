<?php

include_once "db_connection.php";  // Ensure the connection is made after session start

// Check if user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// PHPMailer dependencies
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Function to send email notifications
function sendEmail($recipientEmail, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'vincentmartinez665@gmail.com';
        $mail->Password   = 'rmvwxewltbljiwmd';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        // Sender information
        $mail->setFrom('vincentmartinez665@gmail.com', 'Admin');
        $mail->addReplyTo('vincentmartinez665@gmail.com', 'Admin');

        // Recipient
        $mail->addAddress($recipientEmail);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Prepare and execute query for rankings
$sql = "SELECT students.id, name, points, profile_picture, email FROM students ORDER BY points DESC";
$result = $conn->query($sql);

$rankings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rankings[] = $row;
    }
}

$conn->close();

// Email notification for the top 3 students
foreach ($rankings as $index => $student) {
    if ($index < 3) {  // Send email only to the top 3 students
        $rank = $index + 1;
        $recipientEmail = $student['email'];

        // Check if email is valid
        if (filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            $subject = "Congratulations! You're Ranked #$rank";
            $body = "<p>Dear {$student['name']},</p>
                     <p>Congratulations! You are currently ranked #$rank with {$student['points']} points in the student leaderboard.</p>
                     <p>Keep up the great work!</p>
                     <p>Best regards,<br>Your School Team</p>";

            sendEmail($recipientEmail, $subject, $body);
        } else {
            // Handle invalid email addresses if necessary
            echo "Invalid email address for {$student['name']}<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 3 Ranking Leaderboard</title>
    <link rel="stylesheet" href="studentstyles.css"> <!-- General CSS -->
    <link rel="stylesheet" href="studentstyles.css"> <!-- Student-specific CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('sidebar-open');
            content.classList.toggle('content-open');
        }
    </script>
</head>
<body>
    <div class="menu-icon" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="header">
            <h2>Top 3 Ranking Leaderboard</h2>
        </div>

        <div class="ranking-container">
            <h3>Top 3 Students with highest points</h3>
            <ul>
                <?php foreach ($rankings as $index => $student) : ?>
                    <?php if ($index >= 3) break; ?>
                    <li>
                        <?php if ($index === 0) : ?>
                            <i class="fas fa-trophy trophy" style="color: gold;"></i>
                        <?php elseif ($index === 1) : ?>
                            <i class="fas fa-trophy trophy" style="color: silver;"></i>
                        <?php elseif ($index === 2) : ?>
                            <i class="fas fa-trophy trophy" style="color: #cd7f32;"></i>
                        <?php endif; ?>

                        <div class="rank"><?php echo $index + 1; ?></div>
                        <img src="<?php echo htmlspecialchars($student['profile_picture']); ?>" alt="Profile Picture">
                        <div class="name"><?php echo htmlspecialchars($student['name']); ?></div>
                        <div class="points"><?php echo htmlspecialchars($student['points']); ?> Points</div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
