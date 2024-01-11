<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// function sendEmail($gmail, $subject, $htmlFilePath)
// {
//     //Create an instance; passing `true` enables exceptions
//     $mail = new PHPMailer(true);

//     try {
//         //Server settings
//         $mail->SMTPDebug = SMTP::DEBUG_SERVER;
//         $mail->isSMTP();
//         $mail->Host       = 'smtp.gmail.com';
//         $mail->SMTPAuth   = true;
//         $mail->Username   = 'ejovwogfreeman007@gmail.com';
//         $mail->Password   = 'ayczpdzvosjlxjse'; // Update with your Gmail app password
//         $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
//         $mail->Port       = 465;

//         //Recipients
//         $mail->setFrom('ejovwogfreeman007@gmail.com', 'thegbmedia');
//         $mail->addAddress($gmail);
//         $mail->addReplyTo('ejovwogfreeman007@gmail.com', 'thegbmedia');

//         //Content
//         $mail->isHTML(true);
//         $mail->Subject = $subject;
//         $mail->Body    = file_get_contents($htmlFilePath); // Load HTML content from file
//         $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//         $mail->send();
//         echo 'Message has been sent';
//     } catch (Exception $e) {
//         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//     }
// }


// function sendEmail($gmail, $subject, $htmlFilePath)

function sendEmail($to, $subject, $htmlFilePath, $emailAddress)
{
    // Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ejovwogfreeman007@gmail.com';
        $mail->Password   = 'ayczpdzvosjlxjse'; // Update with your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('ejovwogfreeman007@gmail.com', 'thegbmedia');
        $mail->addAddress($to);
        $mail->addReplyTo('ejovwogfreeman007@gmail.com', 'thegbmedia');

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;

        // Load HTML content from file
        $htmlContent = file_get_contents($htmlFilePath);

        // Replace the placeholder with the actual email address
        $htmlContent = str_replace('{email}', $emailAddress, $htmlContent);

        $mail->Body    = $htmlContent;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
