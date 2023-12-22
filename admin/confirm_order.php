<?php

include('admincheck.php');
include('../config/db.php');
include('../partials/header.php');

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

        if ($order && $order['status'] === 'Processing') {
            // Confirm the order by changing its status to "confirmed"
            $sqlConfirmOrder = "UPDATE orders SET status = 'Confirmed' WHERE order_id = $orderId";
            mysqli_query($conn, $sqlConfirmOrder);

            $message = 'Order Confirmed Successfully';

            redirectWithMessage($message);
        } else {
            $message = 'Order Already Confirmed';

            redirectWithMessage($message);
        }
    } else {
        // Redirect to a page where order_id is provided
        redirectWithMessage($message);
        exit();
    }
}
