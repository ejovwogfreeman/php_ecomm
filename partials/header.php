<?php

// session_start();

function getCartItemCount()
{
    return isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
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
</head>

<body>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg bg-primary py-3 fixed-top">
        <div class="container">
            <!-- navbar brand / title -->
            <a href='/php_ecomm/index.php' class='text-decoration-none'>
                <h2 class='m-0 p-0 text-light'>PhP_Ecomm</h2>
            </a>
            <!-- toggle button for mobile nav -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- navbar links -->
            <div class="collapse navbar-collapse justify-content-end align-center" id="main-nav">
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user']) && $_SESSION['user'][0]['is_admin'] === 'true') : ?>
                        <li class="nav-item"><a href="/php_ecomm/admin" class="nav-link text-light text-decoration-none me-3">Admin</a></li>
                        <li class="nav-item"><a href="/php_ecomm/admin/upload_product.php" class="nav-link text-light text-decoration-none me-3">Upload Product</a></li>
                    <?php endif ?>
                    <?php if (isset($_SESSION['user'])) : ?>
                        <li class="nav-item"><a href="/php_ecomm/dashboard.php" class="nav-link text-light text-decoration-none me-3">Dashboard</a></li>
                        <li class="nav-item"><a href="/php_ecomm/logout.php" class="nav-link text-light text-decoration-none me-3">Logout</a></li>
                        <li class="nav-item"><a href='/php_ecomm/cart.php' class="text-light text-decoration-none" style="position: relative"><i style="font-size: 30px;" class="bi bi-cart"></i><span style="position: absolute; top: -15px; right: -10px; background: red; display: flex; align-items: center; justify-content: center;  width: 25px; height: 25px; border-radius: 50%"><?php echo getCartItemCount(); ?></span></a></li>
                    <?php else : ?>
                        <li class="nav-item"><a href="register.php" class="nav-link text-light text-decoration-none me-3">Register</a></li>
                        <li class="nav-item"><a href="login.php" class="nav-link text-light text-decoration-none me-3">Login</a></li>
                        <li class="nav-item"><a href='/php_ecomm/cart.php' class="text-light text-decoration-none" style="position: relative"><i style="font-size: 30px;" class="bi bi-cart"></i><span style="position: absolute; top: -15px; right: -10px; background: red; display: flex; align-items: center; justify-content: center;  width: 25px; height: 25px; border-radius: 50%"><?php echo getCartItemCount(); ?></span></a></li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </nav>