<?php

ob_start(); // Start output buffering
include('admincheck.php');
include('../config/db.php');

function redirectWithMessage($message)
{
    header('Location: /php_ecommerce/admin/referral_earnings.php?message=' . urlencode($message));
    exit();
}

if (isset($_SESSION['user'])) {
    // Check if order_id is provided in the URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $referralId = $_GET['id'];

        // Fetch order details
        $sql = "SELECT * FROM referral_earnings WHERE referral_earning_id = $referralId";
        $result = mysqli_query($conn, $sql);
        $referralEarning = mysqli_fetch_assoc($result);

        if ($referralEarning) {
            // Check the current status of the order
            switch ($referralEarning['status']) {
                case 'unpaid':
                    // Change status to 'Processing'
                    $sqlUpdateStatus = "UPDATE referral_earnings SET status = 'paid' WHERE referral_earning_id = $referralId";
                    $message = 'Payment Status Changed Successfully!';
                    mysqli_query($conn, $sqlUpdateStatus);
                    redirectWithMessage($message);
                    break;
                case 'paid':
                    $message = 'Payment Status Has Been Changed Already!';
                    redirectWithMessage($message);
                default:
                    $message = 'Invalid Payment Status';
                    redirectWithMessage($message);
                    break;
            }
        } else {
            // Order not found
            $message = 'Invalid Earning ID';
            redirectWithMessage($message);
        }
    } else {
        // Redirect to a page where order_id is provided
        $message = 'Earning ID not provided';
        redirectWithMessage($message);
    }
}

ob_end_flush(); // Flush the output buffer
