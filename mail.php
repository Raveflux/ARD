<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

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

        // Show success message
        echo "<div class='overlay' id='successMessage'>
        <div class='success-message'>Email sent successfully to all.</div>
      </div>
      <script>
        // Hide the message after 2 seconds
        setTimeout(function() {
          document.getElementById('successMessage').style.display = 'none';
        }, 2000);
      </script>";

        
        // Add JavaScript to hide the success message after 2 seconds
        echo "<script>
                setTimeout(function() {
                    var successMessage = document.querySelector('.success-message');
                    if (successMessage) {
                        successMessage.style.opacity = '0'; // Make the message fade out
                    }
                }, 2000); // Delay of 2 seconds
              </script>";
    } catch (Exception $e) {
        // If the email fails to send, show the error message
        echo "<div class='overlay'>
                <div class='error-message'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>
              </div>";
    }
}
?>
