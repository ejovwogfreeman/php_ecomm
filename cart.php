<?php

session_start();
include('./config/db.php');
include('./partials/header.php');

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
        $totalPrice += $quantity * $product['price'];
    }

    $productIds = array_keys($cartProducts);
    $productIdsString = implode(',', $productIds);
    $sql = "SELECT * FROM products WHERE product_id IN ($productIdsString)";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>

<div class="container" style="margin-top: 100px;">
    <div class="d-flex align-items-center justify-content-between my-3">
        <h1 class="m-0">Your Cart</h1>
    </div>

    <?php if (!empty($products)) : ?>
        <div class="row">
            <?php foreach ($products as $product) : ?>
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <?php
                        $imageData = $product['image'];
                        $imageInfo = getimagesizefromstring($imageData);

                        if ($imageInfo !== false) {
                            $imageFormat = $imageInfo['mime'];
                            $img_src = "data:$imageFormat;base64," . base64_encode($imageData);
                        } else {
                            echo "Unable to determine image type.";
                        }
                        ?>
                        <img class="border rounded" src="<?php echo $img_src ?>" alt="<?php echo $product['name'] ?>" style="height: 250px; width: 250px; object-fit: cover">
                        <div class="ms-3">
                            <h3 class="card-title"><?php echo $product['name'] ?></h3>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-text m-0">NGN <?php echo $cartProducts[$product['product_id']] * $product['price'] ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <a href=<?php echo "remove_from_cart.php?id={$product['product_id']}" ?> class="btn btn-primary"><i class="bi bi-trash"></i> Remove</a>
                        <div class="d-flex align-items-center justify-content-between mt-2" style="font-size: 25px;">
                            <a href=<?php echo "decrease.php?id={$product['product_id']}" ?>><i class="bi bi-dash-square-fill"></i></a>
                            <span><?php echo $cartProducts[$product['product_id']]; ?></span>
                            <a href=<?php echo "increase.php?id={$product['product_id']}" ?>><i class="bi bi-plus-square-fill"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
            <div class="border rounded shadow p-3">
                <div class="d-flex align-items-center justify-content-between ">
                    <h3>Total Items: <?php echo $totalQuantity ?></h3>
                    <h3>Total Price: <?php echo $totalPrice ?></h3>
                </div>
                <a href="checkout.php" class="btn btn-primary d-block btn-block p-2">CHECKOUT</a>
            </div>
        </div>
    <?php else : ?>
        <p>No products found in the cart.</p>
    <?php endif ?>
</div>

<?php include('./partials/footer.php'); ?>