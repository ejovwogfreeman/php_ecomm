<?php
session_start();
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
    $username = $user['username'];
}

// Check if the search form is submitted
if (isset($_GET['query'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['query']);
    $sql = "SELECT * FROM products WHERE product_name LIKE '%$searchTerm%' ORDER BY product_id DESC";
} else {
    // If not submitted, fetch all products
    $sql = "SELECT * FROM products ORDER BY product_id DESC";
}

$sql_query = mysqli_query($conn, $sql);
$products = mysqli_fetch_all($sql_query, MYSQLI_ASSOC);
?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Search Products</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body border">
                <form method="GET" action="" class="border border-primary d-flex align-items-center justify-content-between rounded shadow-blue p-1">
                    <i style="font-size: 20px;" class="bi bi-search text-primary ms-2"></i>
                    <input type="text" class="outline-none border-none mx-2" name="query" id="search" placeholder="Search Products..." style="border: none; outline: none; background: transparent; width: 100%">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<div class="container" style="margin-top: 100px;">
    <div class="d-flex align-items-center justify-content-between my-3">
        <div>
            <?php if (isset($_SESSION['user'])) :  ?>
                <h3>Welcome <?php echo $username ?>!</h3>
            <?php endif ?>
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <i style="font-size: 25px;" class="bi bi-search text-primary me-3" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
            <a href="cart.php" class="btn btn-primary">VIEW CART</a>
        </div>
    </div>

    <?php if (isset($_GET['message']) && (strstr($_GET['message'], "successfully")) || (isset($_GET['message']) && (strstr($_GET['message'], "Successfully"))  || (isset($_GET['message']) && (strstr($_GET['message'], "SUCCESSFUL"))))) : ?>
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
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card">
                        <a href=<?php echo "product.php?id={$product['product_id']}" ?> class="text-decoration-none text-dark">
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
                            <img class="card-img-top border" src="<?php echo $img_src ?>" alt="<?php echo $product['product_name'] ?>" style="height: 350px; max-width: 100%; object-fit: cover">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $product['product_name'] ?></h3>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="card-text m-0">NGN <?php echo number_format($product['product_price']) ?></h4>
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