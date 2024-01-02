<?php

// session_start();

// function getCartItemCount()
// {
//     return isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
// }

// session_start();
// require_once('./config/db.php');

$currentUrl = $_SERVER['REQUEST_URI'];

if (strpos($currentUrl, 'admin') !== false) {
    require_once('../config/db.php');
} else {
    require_once('./config/db.php');
}


// Check if the user is logged in (adjust this based on your authentication logic)
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
    $userId = $user['user_id'];

    // Fetch the user's open cart
    $cartQuery = "SELECT * FROM carts WHERE user_id = $userId AND status = 'open'";
    $cartResult = mysqli_query($conn, $cartQuery);

    if (!$cartResult) {
        die("Error fetching user's cart: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($cartResult) > 0) {
        // User has an open cart
        $cart = mysqli_fetch_assoc($cartResult);
        $cartId = $cart['cart_id'];

        // Fetch cart items from the database
        $cartItemsQuery = "SELECT ci.*, p.* FROM cart_items ci JOIN products p ON ci.product_id = p.product_id WHERE ci.cart_id = $cartId";
        $cartItemsResult = mysqli_query($conn, $cartItemsQuery);

        if (!$cartItemsResult) {
            die("Error fetching cart items: " . mysqli_error($conn));
        }

        $products = mysqli_fetch_all($cartItemsResult, MYSQLI_ASSOC);

        $totalQuantity = 0;
        $totalPrice = 0; // Initialize total price
        $uniqueProductIds = [];

        foreach ($products as $cartItem) {
            $productId = $cartItem['product_id'];

            // Check if the product ID is already in the list of unique IDs
            if (!in_array($productId, $uniqueProductIds)) {
                $uniqueProductIds[] = $productId; // Add the product ID to the list
                $totalQuantity += $cartItem['quantity'];
                $totalPrice += $cartItem['quantity'] * $cartItem['product_price'];
            }
        }
    } else {
        // echo "You don't have an open cart.";
    }
} else {
    // echo "User not logged in.";
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP ECOMM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;100&family=Roboto&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: bold;
            margin: 0px;
            padding: 0px;
        }

        .form-style {
            width: 60%;

            @media (max-width: 991px) {
                width: 100%;
            }
        }

        .carousel {
            margin-top: 76px;

            @media (max-width: 991px) {
                margin-top: 72px;
            }
        }

        .carousel img {
            filter: brightness(50%);
        }

        ul,
        li {
            list-style-type: none;
            padding-left: 0px;
        }

        .sidebar li {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 10px;
            display: flex;
            align-items: center;
        }

        .sidebar li:last-child {
            border-bottom: none;
        }

        .sidebar li i {
            font-size: 20px;
            margin-top: -3px;
            margin-right: 10px;
            color: #1976D2;
        }

        .sidebar li .a {
            color: #1976D2;

            @media (max-width: 400px) {
                display: none;
            }
        }

        a {
            text-decoration: none;
        }

        .profile {
            margin-left: 10px;

            @media (max-width: 991px) {
                width: 100%;
            }
        }

        .profile-image {
            border: 3px solid #1976D2;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            display: block;
            margin: auto;

            @media (max-width: 400px) {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>

<body>

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg bg-primary py-3 fixed-top">
        <div class="container">
            <!-- navbar brand / title -->
            <a href='/php_ecommerce/' class='text-decoration-none'>
                <h2 class='m-0 p-0 text-light'>PhP_Ecomm</h2>
            </a>
            <!-- toggle button for mobile nav -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- navbar links -->
            <div class="collapse navbar-collapse justify-content-end align-center" id="main-nav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="/php_ecommerce/" class="nav-link text-light text-decoration-none mt-1 me-3">Home</a></li>
                    <li class="nav-item"><a href="/php_ecommerce/#categories" class="nav-link text-light text-decoration-none mt-1 me-3">Categories</a></li>
                    <?php if (isset($_SESSION['user'])) : ?>
                        <li class="nav-item"><a href="/php_ecommerce/dashboard.php" class="nav-link text-light text-decoration-none mt-1 me-3">Dashboard</a></li>
                        <li class="nav-item"><a href="/php_ecommerce/logout.php" class="nav-link text-light text-decoration-none mt-1 me-3">Logout</a></li>
                        <li class="nav-item"><a href='/php_ecommerce/cart.php' class="text-light text-decoration-none" style="position: relative"><i style="font-size: 30px;" class="bi bi-cart"></i><span style="position: absolute; top: -15px; right: -10px; background: red; display: flex; align-items: center; justify-content: center;  width: 25px; height: 25px; border-radius: 50%"><?php echo isset($uniqueProductIds) ? count($uniqueProductIds) : 0 ?></span></a></li>
                    <?php else : ?>
                        <li class="nav-item"><a href="register.php" class="nav-link text-light text-decoration-none mt-1 me-3">Register</a></li>
                        <li class="nav-item"><a href="login.php" class="nav-link text-light text-decoration-none mt-1 me-3">Login</a></li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </nav>