<?php

ob_start(); // Start output buffering
include('admincheck.php');
include('../config/db.php');

function redirectWithMessage($message)
{
    header('Location: /php_ecommerce/admin/index.php?message=' . urlencode($message));
    exit();
}

if (isset($_SESSION['user'])) {
    // Check if order_id is provided in the URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $orderId = $_GET['id'];

        // Fetch order details
        $sqlOrder = "SELECT * FROM orders WHERE order_id = $orderId";
        $resultOrder = mysqli_query($conn, $sqlOrder);
        $order = mysqli_fetch_assoc($resultOrder);

        if ($order) {
            // Check the current status of the order
            switch ($order['status']) {
                case 'Pending':
                    // Change status to 'Processing'
                    $sqlUpdateStatus = "UPDATE orders SET status = 'Processing' WHERE order_id = $orderId";
                    $message = 'Order moved to Processing Successfully!';
                    // Update the order status based on the conditions
                    mysqli_query($conn, $sqlUpdateStatus);
                    redirectWithMessage($message);
                    break;
                case 'Processing':
                    // Check if the status is 'Cancelled'
                    if ($order['status'] === 'Cancelled') {
                        $message = 'Cannot process a cancelled order!';
                        redirectWithMessage($message);
                    } else {
                        // Change status to 'Confirmed'
                        $sqlUpdateStatus = "UPDATE orders SET status = 'Confirmed' WHERE order_id = $orderId";
                        $message = 'Order Confirmed Successfully!';
                        // Update the order status based on the conditions
                        mysqli_query($conn, $sqlUpdateStatus);
                    }
                    redirectWithMessage($message);
                    break;
                case 'Confirmed':
                    // Check if the status is 'Cancelled'
                    if ($order['status'] === 'Cancelled') {
                        $message = 'Cannot process a cancelled order!';
                        redirectWithMessage($message);
                    } else if ($order['status'] === 'Confirmed') {
                        $message = 'Order is already Confirmed';
                        redirectWithMessage($message);
                        break;
                    }
                case 'Cancelled':
                    $message = 'Order has already been cancelled!';
                    redirectWithMessage($message);
                default:
                    $message = 'Invalid Order Status';
                    redirectWithMessage($message);
                    break;
            }
        } else {
            // Order not found
            $message = 'Invalid Order ID';
            redirectWithMessage($message);
        }
    } else {
        // Redirect to a page where order_id is provided
        $message = 'Order ID not provided';
        redirectWithMessage($message);
    }
}

ob_end_flush(); // Flush the output buffer
