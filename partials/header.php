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
    <nav class='py-3 border-bottom bg-primary'>
        <div class="container d-flex align-items-center justify-content-between">
            <a href='index.php' class='text-decoration-none'>
                <h2 class='m-0 p-0 text-light'>PhP_Ecomm</h2>
            </a>
            <a href='/php_ecomm/cart.php' class="text-light text-decoration-none" style="position: relative"><i style="font-size: 30px;" class="bi bi-cart"></i><span style="position: absolute; top: 0px; right: -10px; background: red; display: flex; align-items: center; justify-content: center;  width: 25px; height: 25px; border-radius: 50%"><?php echo getCartItemCount(); ?></span></a>
        </div>
    </nav>