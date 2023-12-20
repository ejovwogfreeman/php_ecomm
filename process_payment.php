<?php

include('./config/session.php');
include('./config/db.php');

$cartProducts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (!empty($cartProducts)) {
    $totalQuantity = 0;
    $totalPrice = 0;
    $counter = 1;

    foreach ($cartProducts as $productId => $quantity) {
        $totalQuantity += $quantity;

        $sql = "SELECT * FROM products WHERE product_id = $productId";
        $result = mysqli_query($conn, $sql);
        $product = mysqli_fetch_assoc($result);

        $totalPrice += $quantity * $product['price'];
    }

    $productIds = array_keys($cartProducts);
    $productIdsString = implode(',', $productIds);
    $sql = "SELECT * FROM products WHERE product_id IN ($productIdsString)";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
}

$amount = $totalPrice;
$first_name = $user['first_name'];
$last_name = $user['last_name'];
$email = $user['email'];

// Create a unique transaction reference using the current timestamp
$transaction_reference = time();

$shippingAddress = $_GET['shippingAddress'];

date_default_timezone_set('UTC');

// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');

// Prepare payment request data
$request = array(
    'tx_ref' => $transaction_reference,
    'amount' => $amount,
    'currency' => 'NGN',
    'payment_options' => 'card',
    'redirect_url' => 'http://localhost/php_ecommerce/payment_successful.php',
    'customer' => array(
        'email' => $email,
        'name' => $first_name . ' ' . $last_name,
    ),
    'meta' => array(
        'price' => $amount,
    ),
    'customizations' => array(
        'title' => 'Paying for a service', // Set your title
        'description' => 'Level',
    ),
);

// Call Flutterwave endpoint
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.flutterwave.com/v3/payments',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($request),
    CURLOPT_HTTPHEADER => array(
        'Authorization: FLWSECK_TEST-3336815230d55628321ee8dc58ca6195-X',
        'Content-Type: application/json',
    ),
));

$response = curl_exec($curl);

curl_close($curl);

$res = json_decode($response, true);

if ($res['status'] == 'success') {
    // Payment was successful, proceed to place the order

    // Insert order details into the orders table
    $status = 'Processing'; // Default status
    $sqlOrder = "INSERT INTO orders (user_id, shipping_address, total_price, status, date_ordered) VALUES ({$user['user_id']}, '$shippingAddress', $totalPrice, '$status', '$currentDateTime')";
    mysqli_query($conn, $sqlOrder);

    // Get the order_id of the inserted order
    $orderId = mysqli_insert_id($conn);

    // Insert order items into the order_items table
    foreach ($cartProducts as $productId => $quantity) {
        // Fetch product details from the database
        $sqlProduct = "SELECT * FROM products WHERE product_id = $productId";
        $resultProduct = mysqli_query($conn, $sqlProduct);
        $product = mysqli_fetch_assoc($resultProduct);


        // Insert order item details
        $productName = $product['name'];
        $pricePaid = $product['price'] * $quantity; // Assuming product_price is the unit price
        $sqlOrderItem = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price_paid) VALUES ('$orderId', '$productId', '$productName', '$quantity', '$pricePaid')";
        mysqli_query($conn, $sqlOrderItem);
    }

    // Clear the cart after placing the order
    unset($_SESSION['cart']);

    // Redirect to the success page
    $link = $res['data']['link'];
    header('Location: ' . $link);
} else {
    // Handle the case where the payment request was not successful
    echo 'Error: ' . $res['message'];
}
