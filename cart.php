<?php

session_start();
include('./config/db.php');
include('./partials/header.php');

// Retrieve product IDs and quantities from the session cart
$cartProducts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Fetch products that are in the cart
if (!empty($cartProducts)) {
    $totalQuantity = 0;
    $totalPrice = 0; // Initialize total price

    foreach ($cartProducts as $productId => $quantity) {
        $totalQuantity += $quantity;

        // Fetch product details from the database
        $sql = "SELECT * FROM products WHERE product_id = $productId";
        $result = mysqli_query($conn, $sql);
        $product = mysqli_fetch_assoc($result);

        // Calculate and accumulate total price
        $totalPrice += $quantity * $product['product_price'];
    }

    $productIds = array_keys($cartProducts);
    $productIdsString = implode(',', $productIds);
    $sql = "SELECT * FROM products WHERE product_id IN ($productIdsString)";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>

<style>
    .img-style-cart {
        height: 250px;
        width: 250px;
        object-fit: cover;

        @media (max-width: 450px) {
            width: 100%;
            height: auto
        }
    }

    .hide-info {

        display: block;

        @media (max-width: 450px) {
            display: none;
        }
    }

    .show-info {
        display: none;

        @media (max-width: 450px) {
            display: flex;
        }
    }
</style>

<div class="container" style="margin-top: 100px;">
    <div class="d-flex align-items-center justify-content-between my-3">
        <a href="index.php" style="font-size: 30px;"><i class="bi bi-arrow-left-circle-fill"></i></a>
        <h3 class="m-0">Your Cart</h3>
    </div>

    <?php if (!empty($products)) : ?>
        <div class="row">
            <?php foreach ($products as $product) : ?>
                <div class="d-md-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <?php
                        $imageData = $product['product_image'];
                        $imageInfo = getimagesizefromstring($imageData);

                        if ($imageInfo !== false) {
                            $imageFormat = $imageInfo['mime'];
                            $img_src = "data:$imageFormat;base64," . base64_encode($imageData);
                        } else {
                            echo "Unable to determine image type.";
                        }
                        ?>
                        <img class="border img-style-cart" src="<?php echo $img_src ?>" alt="<?php echo $product['product_name'] ?>">
                        <div class="ms-3 hide-info">
                            <h3 class="card-title"><?php echo $product['product_name'] ?></h3>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-text m-0">NGN <?php echo number_format($cartProducts[$product['product_id']] * $product['product_price']) ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 align-items-center justify-content-between show-info">
                        <h3 class="card-title"><?php echo $product['product_name'] ?></h3>
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-text m-0">NGN <?php echo number_format($cartProducts[$product['product_id']] * $product['product_price']) ?></h4>
                        </div>
                    </div>
                    <div class="d-md-block mt-md-0 mt-2 d-flex align-items-center justify-content-between">
                        <a href=<?php echo "remove_from_cart.php?id={$product['product_id']}" ?> class="btn btn-primary"><i class="bi bi-trash"></i> Remove</a>
                        <div class="d-flex align-items-center justify-content-between mt-md-2" style="font-size: 25px;">
                            <a href=<?php echo "decrease.php?id={$product['product_id']}" ?>><i class="bi bi-dash-square-fill"></i></a>
                            <span class="mx-3"><?php echo $cartProducts[$product['product_id']]; ?></span>
                            <a href=<?php echo "increase.php?id={$product['product_id']}" ?>><i class="bi bi-plus-square-fill"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
            <div class="border rounded shadow p-3">
                <div class="d-sm-flex align-items-center justify-content-between mb-2">
                    <h3>Total Items: <?php echo $totalQuantity ?></h3>
                    <h3>Total Price: NGN <?php echo number_format($totalPrice) ?></h3>
                </div>
                <a href="checkout.php" class="btn btn-primary d-block btn-block p-2">CHECKOUT</a>
            </div>
        </div>
    <?php else : ?>
        <p>No products found in the cart.</p>
    <?php endif ?>
</div>

<?php include('./partials/footer.php'); ?>