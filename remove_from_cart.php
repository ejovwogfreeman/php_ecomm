<?php

session_start();

function redirectWithMessage($message)
{
    header('Location: index.php?message=' . urlencode($message));
    exit();
}


if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Check if the product is in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // Remove the product from the cart
        unset($_SESSION['cart'][$productId]);
        $message = 'Product remove from cart successfully';
        redirectWithMessage($message);
    } else {
        // Product is not in the cart
        $message = "Product is not in the cart.";
        redirectWithMessage($message);
    }
} else {
    $message = "Invalid request";
    redirectWithMessage($message);
}
