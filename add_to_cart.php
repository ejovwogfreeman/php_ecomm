<?php

session_start();
include('./config/db.php');
include('./partials/header.php');

$productId = $_GET['id'];

function redirectWithMessage($message)
{
    header('Location: index.php?message=' . urldecode($message));
    return;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] = 1;
    $message = 'Product added successfully';
    redirectWithMessage($message);
} else {
    $message = 'Product already exist in cart';
    redirectWithMessage($message);
}
