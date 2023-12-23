<?php

include('admincheck.php');
include('../config/db.php');
include('../partials/header.php');

function redirectWithMessage($message)
{
    header('Location: /php_ecommerce/index.php?message=' . urlencode($message));
    exit();
}

if (isset($_SESSION['user'])) {
    // Check if order_id is provided in the URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $productId = $_GET['id'];

        // Delete the product
        $sqlDeleteProduct = "DELETE FROM products WHERE product_id = $productId";
        mysqli_query($conn, $sqlDeleteProduct);

        $message = 'Product Deleted Successfully';

        redirectWithMessage($message);
    } else {
        // Redirect to a page where product_id is provided
        redirectWithMessage('Product ID not provided');
    }
} else {
    // Redirect to a page where order_id is provided
    redirectWithMessage($message);
    exit();
}
