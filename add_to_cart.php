<?php

// session_start();

// function redirectWithMessage($message)
// {
//     header('Location: index.php?message=' . urlencode($message));
//     exit();
// }

// if (isset($_GET['id'])) {
//     $productId = $_GET['id'];

//     // Initialize the cart if not already done
//     if (!isset($_SESSION['cart'])) {
//         $_SESSION['cart'] = [];
//     }

//     // Check if the product is already in the cart
//     if (!isset($_SESSION['cart'][$productId])) {
//         // Add product to the cart with a default quantity of 1
//         $_SESSION['cart'][$productId] = 1;
//         $message = "Product added to the cart successfully!";
//         redirectWithMessage($message);
//     } else {
//         // Product is already in the cart
//         $message = "Product is already in the cart.";
//         redirectWithMessage($message);
//     }
// } else {
//     $message = "Invalid request";
//     redirectWithMessage($message);
// }


include('./config/session.php');
include('./config/db.php');

function redirectWithMessage($message)
{
    header('Location: index.php?message=' . urlencode($message));
    exit();
}

// Check if the user is logged in (adjust this based on your authentication logic)
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
    $userId = $user['user_id'];
}

$productId = isset($_GET['id']) ? $_GET['id'] : null;

// Check if the user has an open cart
$checkCartQuery = "SELECT cart_id, status FROM carts WHERE user_id = '$userId' AND status = 'open'";
$resultCart = mysqli_query($conn, $checkCartQuery);

if (!$resultCart) {
    $message = "Error checking user's cart: " . mysqli_error($conn);
    redirectWithMessage($message);
}

if (mysqli_num_rows($resultCart) == 0) {
    // If the user doesn't have an open cart, create a new one with status 'open' and current timestamp
    $createCartQuery = "INSERT INTO carts (user_id, status, created_at) VALUES ('$userId', 'open', NOW())";
    $resultCreateCart = mysqli_query($conn, $createCartQuery);

    if (!$resultCreateCart) {
        $message = "Error creating cart: " . mysqli_error($conn);
        redirectWithMessage($message);
    }

    // Get the newly created cart ID
    $cartId = mysqli_insert_id($conn);
} else {
    // If the user already has an open cart, get its ID
    $cartRow = mysqli_fetch_assoc($resultCart);
    $cartId = $cartRow['cart_id'];
}

// Fetch the product name based on product ID
// $fetchProductQuery = "SELECT product_name FROM products WHERE product_id = '$productId'";
$fetchProductQuery = "SELECT * FROM products WHERE product_id = '$productId'";
$resultProduct = mysqli_query($conn, $fetchProductQuery);

if (!$resultProduct) {
    $message = "Error fetching product details: " . mysqli_error($conn);
    redirectWithMessage($message);
}

$productRow = mysqli_fetch_assoc($resultProduct);
$productName = $productRow['product_name'];

// Check if the item is already in the cart
$checkItemQuery = "SELECT * FROM cart_items WHERE cart_id = '$cartId' AND product_id = '$productId'";
$resultItem = mysqli_query($conn, $checkItemQuery);

if (!$resultItem) {
    $message = "Error checking item in the cart: " . mysqli_error($conn);
    redirectWithMessage($message);
}

$quantity = 1; // Set quantity to 1 by default

$pricePaid = $quantity * $productRow['product_price'];

if (mysqli_num_rows($resultItem) == 0) {
    // Item is not in the cart, add it
    $insertItemQuery = "INSERT INTO cart_items (cart_id, product_id, product_name, quantity, price_paid) VALUES ('$cartId', '$productId', '$productName', '$quantity', '$pricePaid')";
    $resultInsertItem = mysqli_query($conn, $insertItemQuery);

    if ($resultInsertItem) {
        $message = "Item added to the cart successfully!";
        redirectWithMessage($message);
    } else {
        $message = "Error adding item to the cart: " . mysqli_error($conn);
        redirectWithMessage($message);
    }
} else {
    // Item is already in the cart, update quantity
    $message = 'Item already exist in the cart';
    redirectWithMessage($message);
}
