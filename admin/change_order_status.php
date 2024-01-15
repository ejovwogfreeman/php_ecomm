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

                        $referee_id = $order['user_id'];

                        $referee_sql = "SELECT * FROM users WHERE user_id = '$referee_id'";
                        $referee_query = mysqli_query($conn, $referee_sql);
                        $referee = mysqli_fetch_assoc($referee_query);

                        $referee_username = $referee['username'];
                        $referrer_code = $referee['referrer_code'];

                        if (!empty($referrer_code)) {

                            $referrer_sql = "SELECT * FROM users WHERE username = '$referrer_code'";
                            $referrer_query = mysqli_query($conn, $referrer_sql);
                            $referrer = mysqli_fetch_assoc($referrer_query);

                            $referrer_id = $referrer['user_id'];

                            // Fetch the order items
                            $sqlOrderItems = "SELECT * FROM order_items WHERE order_id = $orderId";
                            $resultOrderItems = mysqli_query($conn, $sqlOrderItems);
                            $orderItems = mysqli_fetch_all($resultOrderItems, MYSQLI_ASSOC);

                            $amount = $order['total_price'];

                            $comission_earned = (5 * $amount) / 100;

                            // Initialize an empty string to store order items
                            $orderItemsString = '';

                            // Initialize referral_earning of the user;
                            $referral_earning = (int)$referrer['referral_earning'] + (int)$comission_earned;

                            // Iterate through the order items and concatenate them into a string
                            foreach ($orderItems as $item) {
                                $orderItemsString .= "Product: " . $item['product_name'] . ", Quantity: " . $item['quantity'] . ", Amount: " . $item['price_paid'] . "\n";
                            }

                            // If you want to insert $orderItemsString into another table, you can use an INSERT query
                            $sqlInsert = "INSERT INTO referral_earnings (referrer_user_id, referrer_code, referee_user_id,  referee_username, amount, comission_earned, description, status, date_earned) VALUES ('$referrer_id', '$referrer_code', '$referee_id', '$referee_username', '$amount', '$comission_earned', '$orderItemsString', 'unpaid', NOW())";
                            mysqli_query($conn, $sqlInsert);

                            $updateReferralEarning = "UPDATE users SET referral_earning = '$referral_earning' WHERE user_id = $referrer_id";
                            mysqli_query($conn, $updateReferralEarning);

                            $message = 'Order Confirmed Successfully!';
                            mysqli_query($conn, $sqlUpdateStatus);
                        } else {
                            $message = 'Order Confirmed Successfully!';
                            mysqli_query($conn, $sqlUpdateStatus);
                        }
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
