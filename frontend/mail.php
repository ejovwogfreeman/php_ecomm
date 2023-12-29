<?php

// Enable error reporting to display any warnings or errors
error_reporting(E_ALL);

$to = "naijaexplugtv@gmail.com";
$subject = "Test Email";
$message = "This is a test email.";

$headers = "From: GB MEDIA";

// Use mail() function to send the email
$mailResult = mail($to, $subject, $message, $headers);

// Check the result of the mail() function
if ($mailResult) {
    echo "Email sent successfully!";
} else {
    // Display any additional error information
    echo "Error sending email." . error_get_last();
}
