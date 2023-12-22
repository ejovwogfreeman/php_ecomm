<?php

session_start();

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Check if the product is in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // Increase the quantity by 1
        $_SESSION['cart'][$productId]++;
        header('Location: cart.php');
    } else {
        // Product is not in the cart
        $message = "Product is not in the cart.";
    }
} else {
    $message = "Invalid request";
}
