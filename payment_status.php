<?php


include('./config/session.php');
include('./config/db.php');

// Check if there are products in the cart
$cartProducts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$phoneNum = $_GET['phoneNum'];
$shippingAddress = $_GET['shippingAddress'];

if (!empty($cartProducts)) {
    $totalQuantity = 0;
    $totalPrice = 0;

    foreach ($cartProducts as $productId => $quantity) {
        $totalQuantity += $quantity;

        $sql = "SELECT * FROM products WHERE product_id = $productId";
        $result = mysqli_query($conn, $sql);
        $product = mysqli_fetch_assoc($result);

        $totalPrice += $quantity * $product['product_price'];
    }

    // Fetch product details for the selected products
    $productIds = array_keys($cartProducts);
    $productIdsString = implode(',', $productIds);
    $sql = "SELECT * FROM products WHERE product_id IN ($productIdsString)";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Check if a user is logged in
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
}

$amount = $totalPrice;
$first_name = $user['first_name'];
$last_name = $user['last_name'];
$email = $user['email'];

// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');

if (isset($_GET['status']) && $_GET['status'] === 'completed') {
    // Insert order details into the orders table
    $status = 'Processing'; // Default status
    $sqlOrder = "INSERT INTO orders (user_id, phone_number, shipping_address, total_price, status, date_ordered) VALUES ({$user['user_id']}, '$phoneNum', '$shippingAddress', $totalPrice, '$status', '$currentDateTime')";
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
        $productName = $product['product_name'];
        $pricePaid = $product['product_price'] * $quantity;
        $sqlOrderItem = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price_paid) VALUES ('$orderId', '$productId', '$productName', '$quantity', '$pricePaid')";
        mysqli_query($conn, $sqlOrderItem);
    }

    // Clear the cart after placing the order
    unset($_SESSION['cart']);

    // Redirect to the success page
    header('Location: http://localhost/php_ecommerce/payment_successful.php');
} else {
    // Redirect to the checkout page if payment was not completed
    header('Location: http://localhost/php_ecommerce/checkout.php');
}
