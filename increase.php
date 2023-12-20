<?php

session_start();
include('./config/db.php');
include('./partials/header.php');

$productId = $_GET['id'];

echo $productId;

if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]++;
    header('Location: cart.php');
}
