<?php

session_start();
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
    $username = $user['username'];
}

$sql = "SELECT * FROM products";

$sql_query = mysqli_query($conn, $sql);

$products = mysqli_fetch_all($sql_query, MYSQLI_ASSOC);

?>

<div class="container" style="margin-top: 100px;">
    <div class="d-flex align-items-center justify-content-between my-3">
        <div class="mt-3">
            <?php if (isset($_SESSION['user'])) :  ?>
                <h3>Welcome <?php echo $username ?>!</h3>
            <?php endif ?>
        </div>
        <a href="cart.php" class="btn btn-primary">VIEW CART</a>
    </div>

    <?php if (isset($_GET['message']) && (strstr($_GET['message'], "successfully"))  || (isset($_GET['message']) && (strstr($_GET['message'], "SUCCESSFUL")))) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?php echo $_GET['message'] ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </div>
    <?php elseif (isset($_GET['message'])) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><?php echo $_GET['message'] ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </div>
    <?php endif ?>

    <?php if (!empty($products)) : ?>
        <div class="row mt-4">
            <?php foreach ($products as $product) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <a href=<?php echo "product.php?id={$product['product_id']}" ?> class="text-decoration-none text-dark">
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
                            <img class="card-img-top border" src="<?php echo $img_src ?>" alt="<?php echo $product['name'] ?>" style="height: 350px; max-width: 100%; object-fit: cover">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $product['name'] ?></h3>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="card-text m-0">NGN <?php echo $product['price'] ?></h4>
                                    <a href=<?php echo "add_to_cart.php?id={$product['product_id']}" ?> class="btn btn-primary">Add to Cart</a>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php else : ?>
        <p>No products found.</p>
    <?php endif ?>
</div>

<?php include('./partials/footer.php'); ?>