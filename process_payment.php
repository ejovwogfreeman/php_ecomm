<?php

// include('./config/session.php');
// include('./config/db.php');

// // Check if there are products in the cart
// $cartProducts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// if (!empty($cartProducts)) {
//     $totalQuantity = 0;
//     $totalPrice = 0;

//     foreach ($cartProducts as $productId => $quantity) {
//         $totalQuantity += $quantity;

//         $sql = "SELECT * FROM products WHERE product_id = $productId";
//         $result = mysqli_query($conn, $sql);
//         $product = mysqli_fetch_assoc($result);

//         $totalPrice += $quantity * $product['product_price'];
//     }

//     // Fetch product details for the selected products
//     $productIds = array_keys($cartProducts);
//     $productIdsString = implode(',', $productIds);
//     $sql = "SELECT * FROM products WHERE product_id IN ($productIdsString)";
//     $result = mysqli_query($conn, $sql);
//     $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
// }


// // Check if a user is logged in
// if (isset($_SESSION['user'])) {
//     $user = $_SESSION['user'][0];
// }



include('get_cart_items.php');

$amount = $totalPrice;
$first_name = $user['first_name'];
$last_name = $user['last_name'];
$email = $user['email'];

// Create a unique transaction reference using the current timestamp
$transaction_reference = time();

$phoneNum = $_GET['phoneNum'];
$shippingAddress = $_GET['shippingAddress'];

// Prepare payment request data
$request = array(
    'tx_ref' => $transaction_reference,
    'amount' => $amount,
    'currency' => 'NGN',
    'payment_options' => 'card',
    'redirect_url' => 'http://localhost/php_ecommerce/payment_status.php?phoneNum=' . urlencode($phoneNum) . '&shippingAddress=' . urlencode($shippingAddress),
    'customer' => array(
        'email' => $email,
        'name' => $first_name . ' ' . $last_name,
    ),
    'meta' => array(
        'price' => $amount,
    ),
    'customizations' => array(
        'title' => 'Paying for a service',
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
    // Payment was successful, redirect to the Flutterwave page
    $link = $res['data']['link'];
    header('Location: ' . $link);
} else {
    // Handle the case where the payment request was not successful
    echo 'Error: ' . $res['message'];
}
