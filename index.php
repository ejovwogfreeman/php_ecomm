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

function showFlyingAlert($message, $className)
{
    echo <<<EOT
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var alertDiv = document.createElement("div");
            alertDiv.className = "{$className}";
            alertDiv.innerHTML = "{$message}";
            document.body.appendChild(alertDiv);

            // Triggering reflow to enable animation
            alertDiv.offsetWidth;

            // Add a class to trigger the fly-in animation
            alertDiv.style.left = "10px";

            // Remove the fly-in style after 3 seconds
            setTimeout(function() {
                alertDiv.style.left = "10px";
            }, 2000);

            // Add a class to trigger the fly-out animation after 3 seconds
            setTimeout(function() {
                alertDiv.style.left = "-300px";
            }, 4000);

            // Remove the element after the total duration of the animation (9 seconds)
            setTimeout(function() {
                alertDiv.remove();
            }, 6000);
        });
    </script>
EOT;
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];

    if (stristr($message, "successfully") || stristr($message, "Successfully") || stristr($message, "SUCCESSFUL")) {
        showFlyingAlert($message, "flying-success-alert");
    } else {
        showFlyingAlert($message, "flying-danger-alert");
    }
}

?>


<style>
    .flying-success-alert {
        position: fixed;
        z-index: 11111111111111;
        top: 15px;
        left: -300px;
        background-color: #4CAF50;
        color: #fff;
        padding: 10px;
        border-radius: 5px;
        transition: left 1.5s ease-in-out;
    }

    .flying-danger-alert {
        position: fixed;
        z-index: 11111111111111;
        top: 15px;
        left: -300px;
        background-color: #FF5252;
        color: #fff;
        padding: 10px;
        border-radius: 5px;
        transition: left 1.5s ease-in-out;
    }
</style>




<div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item position-relative active" data-bs-interval="10000">
            <img src="images/electronics.jpeg" class="d-block w-100" alt="..." />
            <div class="position-absolute text-light" style="top: 47%; text-align: center; width: 100%">
                <h1>Electronics</h1>
                <p>Some representative placeholder content for the first slide.</p>
                <a href="categories.php?category=electronics" class="btn btn-primary">SHOP NOW</a>
            </div>
        </div>
        <div class="carousel-item position-relative" data-bs-interval="2000">
            <img src="images/img1.jpg" class="d-block w-100" alt="..." />
            <div class="position-absolute text-light" style="top: 47%; text-align: center; width: 100%">
                <h1>Laptops</h1>
                <p>Some representative placeholder content for the second slide.</p>
                <a href="categories.php?category=laptops" class="btn btn-primary">SHOP NOW</a>
            </div>
        </div>
        <div class="carousel-item position-relative" data-bs-interval="3000">
            <img src="images/img2.jpg" class="d-block w-100" alt="..." />
            <div class="position-absolute text-light" style="top: 47%; text-align: center; width: 100%">
                <h1>Accessories</h1>
                <p>Some representative placeholder content for the second slide.</p>
                <a href="categories.php?category=accessories" class="btn btn-primary">SHOP NOW</a>
            </div>
        </div>
        <div class="carousel-item position-relative">
            <img src="images/img3.jpg" class="d-block w-100" alt="..." />
            <div class="position-absolute text-light" style="top: 47%; text-align: center; width: 100%">
                <h1>Phones</h1>
                <p>Some representative placeholder content for the third slide.</p>
                <a href="categories.php?category=phones" class="btn btn-primary">SHOP NOW</a>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<div class="container">
    <!-- <div class="d-flex align-items-center justify-content-between my-3">
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
    <?php endif ?> -->

    <div class="row mt-4" id="categories">
        <h3 class="mb-3">Product Categories</h3>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card">
                <a href=<?php echo "categories.php?category=electronics" ?> class="text-decoration-none text-dark">
                    <img class="card-img-top border" src="images/electronics.jpeg" style="height: 350px; max-width: 100%; object-fit: cover">
                    <div class="card-body">
                        <h3 class="card-title">Electronics</h3>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card">
                <a href=<?php echo "categories.php?category=laptops" ?> class="text-decoration-none text-dark">
                    <img class="card-img-top border" src="images/img1.jpg" style="height: 350px; max-width: 100%; object-fit: cover">
                    <div class="card-body">
                        <h3 class="card-title">Laptops</h3>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card">
                <a href=<?php echo "categories.php?category=accessories" ?> class="text-decoration-none text-dark">
                    <img class="card-img-top border" src="images/img2.jpg" style="height: 350px; max-width: 100%; object-fit: cover">
                    <div class="card-body">
                        <h3 class="card-title">Accessories</h3>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card">
                <a href=<?php echo "categories.php?category=phones" ?> class="text-decoration-none text-dark">
                    <img class="card-img-top border" src="images/img3.jpg" style="height: 350px; max-width: 100%; object-fit: cover">
                    <div class="card-body">
                        <h3 class="card-title">Phone</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- <?php if (!empty($products)) : ?>
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
    <?php endif ?> -->
</div>

<?php include('./partials/footer.php'); ?>