<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['voucher_code'], $_POST['duration'], $_POST['recipient_email'])) {
    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $voucher_code = $_POST['voucher_code'];
    $duration = $_POST['duration'];
    $recipient_email = $_POST['recipient_email'];

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'vincentmartinez665@gmail.com';  // Your email here
        $mail->Password   = 'rmvwxewltbljiwmd';         // Your email password here
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        // Sender information
        $mail->setFrom('vincentmartinez665@gmail.com', 'Admin');
        $mail->addReplyTo('vincentmartinez665@gmail.com', 'Admin');

        // Recipient
        $mail->addAddress($recipient_email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your Voucher Code';
        $mail->Body    = "You have received a voucher with code: $voucher_code. Duration: $duration.";

        // Send the email
        $mail->send();

        echo 'Voucher sent successfully.';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}
?>
