<?php

include('./config/session.php');
include('./config/db.php');
include('./partials/header.php');

$cartProducts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$phoneNum = $shippingAddress  =  $Err = '';

if (!empty($cartProducts)) {
    $totalQuantity = 0;
    $totalPrice = 0;
    $counter = 1;

    foreach ($cartProducts as $productId => $quantity) {
        $totalQuantity += $quantity;

        $sql = "SELECT * FROM products WHERE product_id = $productId";
        $result = mysqli_query($conn, $sql);
        $product = mysqli_fetch_assoc($result);

        $totalPrice += $quantity * $product['product_price'];
    }

    $productIds = array_keys($cartProducts);
    $productIdsString = implode(',', $productIds);
    $sql = "SELECT * FROM products WHERE product_id IN ($productIdsString)";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_POST['phoneNum'])) {
            $Err = 'PLEASE ENTER A PHONE NUMBER';
        } else {
            $phoneNum = htmlspecialchars($_POST['phoneNum']);
            if (empty($_POST['shippingAddress'])) {
                $Err = 'PLEASE ENTER A SHIPPING ADDRESS';
            } else {
                $shippingAddress = htmlspecialchars($_POST['shippingAddress']);
                header("Location: process_payment.php?phoneNum=" . urlencode($phoneNum) . "&shippingAddress=" . urlencode($shippingAddress));
            }
        }
    }
}

?>

<div class="container" style="margin-top: 100px;">
    <div class="d-flex align-items-center justify-content-between my-3">
        <a href="cart.php" style="font-size: 30px;"><i class="bi bi-arrow-left-circle-fill"></i></a>
        <h3 class="m-0">Proceed To Checkout</h3>
    </div>

    <div class="d-lg-flex align-items-center justify-content-between">
        <form style="flex: 1;" class="border rounded shadow p-3" action="checkout.php" method="POST">
            <?php if ($Err) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?php echo $Err ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
            <?php endif ?>
            <h5 class="text-center mt-2">Enter Your shipping Address</h5>
            <div class="my-3">
                <label for="phoneNum" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phoneNum" name="phoneNum" value="<?php echo $phoneNum ?>">
            </div>
            <div class="my-3">
                <label for="shippingAddress" class="form-label">Address/Location</label>
                <input type="text" class="form-control" id="shippingAddress" name="shippingAddress" value="<?php echo $shippingAddress ?>">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%">CHECKOUT</button>
        </form>
        <div style="flex: 2;" class="border rounded ms-lg-3 mt-lg-0 mt-3 p-3">
            <?php if (!empty($products)) : ?>
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th scope="col">S/N</th>
                            <th scope="col">Product</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Price</th>
                        </tr>
                    </thead>
                    <?php foreach ($products as $product) : ?>
                        <tbody>
                            <tr>
                                <th scope="row"><?php echo $counter++; ?></th>
                                <td><?php echo $product['product_name'] ?></td>
                                <td><?php echo $cartProducts[$product['product_id']]; ?></td>
                                <td><?php echo number_format($product['product_price']); ?></td>
                            </tr>
                        </tbody>
                    <?php endforeach ?>
                </table>
                <div class="border rounded p-3">
                    <div class="d-md-flex align-items-center justify-content-between">
                        <h3>Total Items: <?php echo $totalQuantity ?></h3>
                        <h3>Total Price: NGN <?php echo number_format($totalPrice); ?></h3>
                    </div>
                </div>
            <?php else : ?>
                <p>No products found in the cart.</p>
            <?php endif ?>
        </div>
    </div>
</div>

<?php include('./partials/footer.php'); ?>