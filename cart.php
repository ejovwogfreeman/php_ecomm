<?php

session_start();
include('./config/db.php');
include('./partials/header.php');

$cartProducts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (!empty($cartProducts)) {
    $productIds = array_keys($cartProducts);
    $productIdsString = implode(',', $productIds);
    $sql = "SELECT * FROM products WHERE product_id IN ($productIdsString)";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $products = [];
}

?>

<div class="container mt-3">
    <h3>Your Cart</h3>
</div>

<?php include('./partials/footer.php'); ?>