<?php
session_start();
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
    $username = $user['username'];
}


// Fetch the 3 latest products under the "laptop" category
$electronic_sql = "SELECT * FROM products WHERE product_category = 'electronics' ORDER BY product_id DESC";

$electronic_sql_query = mysqli_query($conn, $electronic_sql);
$electronicProducts = mysqli_fetch_all($electronic_sql_query, MYSQLI_ASSOC);

// Fetch the 3 latest products under the "laptop" category
$laptop_sql = "SELECT * FROM products WHERE product_category = 'laptops' ORDER BY product_id DESC";

$laptop_sql_query = mysqli_query($conn, $laptop_sql);
$laptopProducts = mysqli_fetch_all($laptop_sql_query, MYSQLI_ASSOC);

// Fetch the last blog post
$lastblog_sql = "SELECT * FROM blogs ORDER BY created_at DESC LIMIT 1";

$lastblog_sql_query = mysqli_query($conn, $lastblog_sql);
$lastBlogPost = mysqli_fetch_assoc($lastblog_sql_query);

// Fetch the second-to-last blog post
$secondtolast_sql = "SELECT * FROM blogs ORDER BY created_at DESC LIMIT 1 OFFSET 1";

$secondtolast_sql_query = mysqli_query($conn, $secondtolast_sql);
$secondToLastBlogPost = mysqli_fetch_assoc($secondtolast_sql_query);

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

    .header-style {
        top: 40%;
        text-align: center;
        width: 100%;
    }

    .shop-btn {
        border: 1px solid white;
        color: white;
    }

    .shop-btn:hover {
        background-color: white;
        color: black;
    }

    @media (min-width: 991px) {
        #carouselExampleInterval img {
            height: 50vh;
            object-fit: cover;
        }
    }

    .image-bg {
        background-image: url('./images/tech_studio.jpg');
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: 400px;
        display: flex;
        justify-content: right;

        @media (max-width: 991px) {
            height: 600px;
            position: relative;
        }
    }

    .custom-container {
        background-color: white;
        width: 50%;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        padding: 50px;
        padding-left: 50px;
        padding-right: 130px;
        text-align: justify;

        @media (max-width: 991px) {
            width: 100%;
            padding: 50px;
            padding-right: 10%;
            padding-left: 10%;
        }

        @media (max-width: 991px) {
            position: absolute;
            bottom: -10px;
        }
    }

    .show-case {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        position: relative;
        overflow: hidden;

        @media (max-width: 991px) {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .show-case img {
        filter: brightness(50%);
        transition: transform 0.3s ease;
    }

    .show-case img:hover {
        transform: scale(1.1);
        cursor: pointer;
        filter: brightness(70%);
        overflow: hidden;
        z-index: 0;
    }

    .glowing-text-container {
        position: absolute;
        width: 100%;
        text-align: center;
        top: 40%;

        @media (max-width: 400px) {
            top: 30%;
        }

    }

    .glowing-text {
        font-size: 40px;
        color: #fff;
        text-align: center;
        font-weight: bold;
        font-family: 'Arial', sans-serif;
        position: relative;
        display: inline-block;
    }

    .glowing-text::before {
        content: attr(data-text);
        position: absolute;
        top: 0;
        left: 0;
        z-index: -1;
        color: #fff;
        filter: blur(10px);
    }

    .glowing-text::before {
        animation: none;
        z-index: 1;
    }

    .glowing-text {
        text-shadow: 0 0 20px #1976D2;
    }
</style>




<div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item position-relative active" data-bs-interval="10000">
            <img src="images/elect.webp" class="d-block w-100" alt="..." />
            <div class="position-absolute text-light header-style">
                <h1 class="mb-1 m-sm-0">Electronics</h1>
                <p class="d-none d-sm-block">Some representative placeholder content for the first slide.</p>
                <a href="categories.php?category=electronics" class="btn shop-btn">SHOP NOW</a>
            </div>
        </div>
        <div class="carousel-item position-relative" data-bs-interval="2000">
            <img src="images/laptop.jpg" class="d-block w-100" alt="..." />
            <div class="position-absolute text-light header-style">
                <h1 class="mb-1 m-sm-0">Laptops</h1>
                <p class="d-none d-sm-block">Some representative placeholder content for the second slide.</p>
                <a href="categories.php?category=laptops" class="btn shop-btn">SHOP NOW</a>
            </div>
        </div>
        <div class="carousel-item position-relative" data-bs-interval="3000">
            <img src="images/accessories.avif" class="d-block w-100" alt="..." />
            <div class="position-absolute text-light header-style">
                <h1 class="mb-1 m-sm-0">Accessories</h1>
                <p class="d-none d-sm-block">Some representative placeholder content for the second slide.</p>
                <a href="categories.php?category=accessories" class="btn shop-btn">SHOP NOW</a>
            </div>
        </div>
        <div class="carousel-item position-relative">
            <img src="images/phones.jpeg" class="d-block w-100" alt="..." />
            <div class="position-absolute text-light header-style">
                <h1 class="mb-1 m-sm-0">Phones</h1>
                <p class="d-none d-sm-block">Some representative placeholder content for the third slide.</p>
                <a href="categories.php?category=phones" class="btn shop-btn">SHOP NOW</a>
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
    <div class="row mt-5" id="categories">
        <h1 class="mb-4 text-center">Shop by Category</h1>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card">
                <a href=<?php echo "categories.php?category=electronics" ?> class="text-decoration-none text-dark">
                    <img class="card-img-top border" src="images/elect.webp" style="height: 350px; max-width: 100%; object-fit: cover">
                    <div class="card-body">
                        <h3 class="card-title">Electronics</h3>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card">
                <a href=<?php echo "categories.php?category=laptops" ?> class="text-decoration-none text-dark">
                    <img class="card-img-top border" src="images/laptop.jpg" style="height: 350px; max-width: 100%; object-fit: cover">
                    <div class="card-body">
                        <h3 class="card-title">Laptops</h3>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card">
                <a href=<?php echo "categories.php?category=accessories" ?> class="text-decoration-none text-dark">
                    <img class="card-img-top border" src="images/accessories.avif" style="height: 350px; max-width: 100%; object-fit: cover">
                    <div class="card-body">
                        <h3 class="card-title">Accessories</h3>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card">
                <a href=<?php echo "categories.php?category=phones" ?> class="text-decoration-none text-dark">
                    <img class="card-img-top border" src="images/phones.jpeg" style="height: 350px; max-width: 100%; object-fit: cover">
                    <div class="card-body">
                        <h3 class="card-title">Phones</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="image-bg mt-5">
    <div class="custom-container">
        <h1 class="mb-3">Getting You The Best Tech Producs</h1>
        <p>minus sunt, quasi ratione fuga dicta blanditiis, totam tempore rerum repudiandae obcaecati assumenda neque non. Id tenetur amet minima voluptatibus perferendis officia ratione fugiat exercitationem explicabo suscipit ea ipsa animi veritatis cumque eos ad reiciendis eveniet, eaque illo omnis eius dignissimos quo quaerat! Nam, fuga minus.</p>
    </div>
</div>
<div class="bg-secondary">
    <div class="container text-center d-block d-sm-flex align-items-center justify-content-between py-5">
        <h1 class="d-sm-block d-none text-light">We are close to you</h1>
        <A class="btn btn-primary">GET STARTED</A>
    </div>
</div>

<div class="py-4" style="background-color: rgba(0, 0, 0, 0.1);">
    <h1 class="text-center">Shop Electronic Products</h1>
    <div class="container">
        <?php if (!empty($electronicProducts)) : ?>
            <div class="row mt-4">
                <?php foreach ($electronicProducts as $product) : ?>
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
</div>

<div class="container">
    <div class="mt-5">
        <h1 class="mb-4 text-center">Blog Highlight</h1>
        <div class="flex-items1">
            <div style="flex: 1;">
                <?php
                $imageData = $lastBlogPost['blog_image'];
                $imageInfo = getimagesizefromstring($imageData);

                if ($imageInfo !== false) {
                    $imageFormat = $imageInfo['mime'];
                    $img_src = "data:$imageFormat;base64," . base64_encode($imageData);
                } else {
                    echo "Unable to determine image type.";
                }
                ?>
                <img class="border img-style" style="width: 100%; height: 400px; object-fit: cover" src="<?php echo $img_src ?>" alt="<?php echo $blog['blog_title'] ?>">
            </div>
            <div class="content1" style="flex: 1;">
                <h3><?php echo $lastBlogPost['blog_title'] ?></h3>
                <p class="mt-2 mb-3"><?php echo ucfirst(substr($lastBlogPost['blog_content'], 0, 200)) . '...'; ?></p>
                <a href=<?php echo "blog.php?id={$lastBlogPost['blog_id']}" ?> class="btn btn-primary">READ MORE</a>
            </div>
        </div>
        <div class="mt-5 flex-items ">
            <div class="content" style="flex: 1;">
                <h2><?php echo $secondToLastBlogPost['blog_title'] ?></h2>
                <p class="mt-2 mb-3"><?php echo ucfirst(substr($secondToLastBlogPost['blog_content'], 0, 200)) . '...'; ?></p>
                <a href=<?php echo "blog.php?id={$secondToLastBlogPost['blog_id']}" ?> class="btn btn-primary">READ MORE</a>
            </div>
            <div style="flex: 1; padding: 0px">
                <?php
                $imageData = $secondToLastBlogPost['blog_image'];
                $imageInfo = getimagesizefromstring($imageData);

                if ($imageInfo !== false) {
                    $imageFormat = $imageInfo['mime'];
                    $img_src = "data:$imageFormat;base64," . base64_encode($imageData);
                } else {
                    echo "Unable to determine image type.";
                }
                ?>
                <img class="border img-style" style="width: 100%; height: 400px; object-fit: cover" src="<?php echo $img_src ?>" alt="<?php echo $blog['blog_title'] ?>">
            </div>
        </div>
    </div>
</div>

<div class="py-4" style="background-color: rgba(0, 0, 0, 0.1);">
    <h1 class="text-center">Shop Laptop Products</h1>
    <div class="container">
        <?php if (!empty($laptopProducts)) : ?>
            <div class="row mt-4">
                <?php foreach ($laptopProducts as $product) : ?>
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
</div>

<div class="container">
    <div class="mt-5">
        <h1 class="mb-4 text-center">Exclusive Deals</h1>
        <div class="flex-items">
            <div class="content" style="flex: 1;">
                <h3>Set Up Your Own Tech Studio</h3>
                <p class="mt-2 mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel ad quae libero commodi corporis voluptatibus veritatis maxime esse id nostrum. Excepturi fugiat odit quibusdam ea quod, eveniet nam architecto est veritatis tempore doloribus expedita porro recusandae error eaque vitae vel facilis nihil quis illo voluptatibus atque consectetur! Quos, perspiciatis repudiandae...</p>
                <button class="btn btn-primary">READ MORE</button>
            </div>
            <div style="flex: 1;">
                <img src="images/tech_studio.jpg" alt="" width="100%">
            </div>
        </div>
    </div>
</div>

<div class="show-case mt-5">
    <img src="images/tech_studio.jpg" alt="" width="100%">
    <img src="images/tech_studio.jpg" alt="" width="100%">
    <img src="images/tech_studio.jpg" alt="" width="100%">
    <img src="images/tech_studio.jpg" alt="" width="100%">
    <img src="images/tech_studio.jpg" alt="" width="100%">
    <img src="images/tech_studio.jpg" alt="" width="100%">
    <img src="images/tech_studio.jpg" alt="" width="100%">
    <img src="images/tech_studio.jpg" alt="" width="100%">
    <img src="images/tech_studio.jpg" alt="" width="100%">
    <img src="images/tech_studio.jpg" alt="" width="100%">
    <img src="images/tech_studio.jpg" alt="" width="100%">
    <img src="images/tech_studio.jpg" alt="" width="100%">
    <div class="glowing-text-container">
        <div class="glowing-text" data-text="Glowing Text">#TALKTECH</div>
    </div>
</div>
<?php include('./partials/footer.php'); ?>