<?php

// session_start();

// function redirectWithMessage($message)
// {
//     header('Location: index.php?message=' . urlencode($message));
//     exit();
// }


// if (isset($_GET['id'])) {
//     $productId = $_GET['id'];

//     // Check if the product is in the cart
//     if (isset($_SESSION['cart'][$productId])) {
//         // Remove the product from the cart
//         unset($_SESSION['cart'][$productId]);
//         $message = 'Product remove from cart successfully';
//         redirectWithMessage($message);
//     } else {
//         // Product is not in the cart
//         $message = "Product is not in the cart.";
//         redirectWithMessage($message);
//     }
// } else {
//     $message = "Invalid request";
//     redirectWithMessage($message);
// }


include('./config/session.php');
include('./config/db.php');

function redirectWithMessage($message)
{
    header('Location: index.php?message=' . urlencode($message));
    exit();
}

// Check if the user is logged in (adjust this based on your authentication logic)
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
    $userId = $user['user_id'];
} else {
    $message = "User not logged in";
    redirectWithMessage($message);
}

$productId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$productId) {
    $message = "Product ID not provided";
    redirectWithMessage($message);
}

// Check if the user has an open cart
$checkCartQuery = "SELECT cart_id FROM carts WHERE user_id = '$userId' AND status = 'open'";
$resultCart = mysqli_query($conn, $checkCartQuery);

if (!$resultCart) {
    $message = "Error checking user's cart: " . mysqli_error($conn);
    redirectWithMessage($message);
}

if (mysqli_num_rows($resultCart) > 0) {
    // User has an open cart, get its ID
    $cartRow = mysqli_fetch_assoc($resultCart);
    $cartId = $cartRow['cart_id'];

    // Check if the item is in the cart
    $checkItemQuery = "SELECT * FROM cart_items WHERE cart_id = '$cartId' AND product_id = '$productId'";
    $resultItem = mysqli_query($conn, $checkItemQuery);

    if (!$resultItem) {
        $message = "Error checking item in the cart: " . mysqli_error($conn);
        redirectWithMessage($message);
    }

    if (mysqli_num_rows($resultItem) > 0) {
        // Item is in the cart, remove it
        $removeItemQuery = "DELETE FROM cart_items WHERE cart_id = '$cartId' AND product_id = '$productId'";
        $resultRemoveItem = mysqli_query($conn, $removeItemQuery);

        if ($resultRemoveItem) {
            $message = "Item removed from the cart successfully!";
            redirectWithMessage($message);
        } else {
            $message = "Error removing item from the cart: " . mysqli_error($conn);
            redirectWithMessage($message);
        }
    } else {
        $message = "Item not found in the cart";
        redirectWithMessage($message);
    }
} else {
    $message = "User does not have an open cart";
    redirectWithMessage($message);
}
