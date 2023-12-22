<?php

session_start();

function redirectWithMessage($message)
{
    header('Location: index.php?message=' . urlencode($message));
    exit();
}

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Initialize the cart if not already done
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (!isset($_SESSION['cart'][$productId])) {
        // Add product to the cart with a default quantity of 1
        $_SESSION['cart'][$productId] = 1;
        $message = "Product added to the cart successfully!";
        redirectWithMessage($message);
    } else {
        // Product is already in the cart
        $message = "Product is already in the cart.";
        redirectWithMessage($message);
    }
} else {
    $message = "Invalid request";
    redirectWithMessage($message);
}
