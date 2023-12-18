<?php

session_start();
include('./config/db.php');
include('./partials/header.php');

$id = $_GET['id'];

$sql = "SELECT * FROM products WHERE product_id = '$id'";

$sql_query = mysqli_query($conn, $sql);

$product = mysqli_fetch_all($sql_query, MYSQLI_ASSOC)[0];

?>

<div class="container mt-5">
    <div class="d-flex align-items-center justify-content-between mb-3 pb-3">
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
            <img class="border" src="<?php echo $img_src ?>" alt="<?php echo $product['name'] ?>" style="height: 500px; width: 500px; object-fit: cover">
            <div class="ms-3 d-flex flex-column justify-content-between">
                <h3 class="card-title"><?php echo $product['name'] ?></h3>
                <P class="my-5"><?php echo $product['description'] ?></P>
                <div class=" d-flex align-items-center justify-content-between">
                    <h4 class="card-text me-5">NGN <?php echo $product['price'] ?></h4>
                    <a href=<?php echo "remove_from_cart.php?id={$product['product_id']}" ?> class="btn btn-primary">ADD TO CART</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('./partials/footer.php'); ?>