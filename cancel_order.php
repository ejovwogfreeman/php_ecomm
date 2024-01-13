<?php

ob_start(); // Start output buffering
include('./config/session.php');
include('./config/db.php');


function redirectWithMessage($message)
{
    // Check if the user is an admin
    $isAdmin = isset($_SESSION['user']) && $_SESSION['user'][0]['is_admin'] === 'true';

    // Set the redirect page based on admin status
    $redirectPage = $isAdmin ? '/php_ecommerce/admin/index.php' : '/php_ecommerce/orders.php';

    // Redirect to the determined page with the message
    header('Location: ' . $redirectPage . '?message=' . urlencode($message));
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
                    $sqlUpdateStatus = "UPDATE orders SET status = 'Cancelled' WHERE order_id = $orderId";
                    $message = 'Order has been cancelled successfully!';
                    // Update the order status based on the conditions
                    mysqli_query($conn, $sqlUpdateStatus);
                    redirectWithMessage($message);
                    break;
                case 'Processing':
                    $message = 'Order is already processing, cannot cancel order!';
                    redirectWithMessage($message);
                    break;
                case 'Confirmed':
                    $message = 'Order already confirmed, cannot cancel order!';
                    redirectWithMessage($message);
                    break;
                case 'Cancelled':
                    $message = 'Order has already been cancelled!';
                    redirectWithMessage($message);
                    break;
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
