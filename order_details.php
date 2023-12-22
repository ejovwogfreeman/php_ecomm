<?php

include('./config/session.php');
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    // Check if order_id is provided in the URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $orderId = $_GET['id'];

        // Fetch order details
        $sqlOrder = "SELECT * FROM orders WHERE order_id = $orderId";
        $resultOrder = mysqli_query($conn, $sqlOrder);
        $order = mysqli_fetch_assoc($resultOrder);

        // Fetch order items
        $sqlOrderItems = "SELECT * FROM order_items WHERE order_id = $orderId";
        $resultOrderItems = mysqli_query($conn, $sqlOrderItems);
        $orderItems = mysqli_fetch_all($resultOrderItems, MYSQLI_ASSOC);

        $counter = 1;
    }
}
?>

<div class="container" style="margin-top: 100px;">

    <div class=" my-3">
        <a href="dashboard.php" style="font-size: 30px;"><i class="bi bi-arrow-left-circle-fill"></i></a>
    </div>

    <div class="border rounded p-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Order Details - Order #<?php echo $orderId; ?></h3>
            <?php if (isset($_SESSION['user']) && $_SESSION['user'][0]['is_admin'] === 'true') : ?>
                <a href=<?php echo "/php_ecommerce/admin/confirm_order.php?id={$order['order_id']}" ?> class="btn btn-outline-success">Confirm Order</a>
            <?php endif ?>
        </div>
        <div class="mt-3">
            <p><strong>Shipping Address:</strong> <?php echo $order['shipping_address']; ?></p>
            <p><strong>Total Price (NGN):</strong> <?php echo number_format($order['total_price']); ?></p>
            <p><strong>Date Ordered:</strong> <?php echo $order['date_ordered']; ?></p>
            <p><strong>Status:</strong>
                <small class="<?php echo $order['status'] === 'Processing' ? 'bg-warning' : 'bg-success'; ?> text-light p-1 rounded">
                    <?php echo ucfirst($order['status']); ?>
                </small>
            </p>
        </div>
    </div>

    <?php if (!empty($orderItems)) : ?>
        <table class="table text-center">
            <thead>
                <tr>
                    <th scope="col">S/N</th>
                    <th scope="col">Product</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price Paid (NGN)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item) : ?>
                    <tr>
                        <th scope="row"><?php echo $counter++ ?></th>
                        <td><?php echo $item['product_name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo number_format($item['price_paid']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No items found for this order.</p>
    <?php endif; ?>

</div>

<?php include('./partials/footer.php'); ?>