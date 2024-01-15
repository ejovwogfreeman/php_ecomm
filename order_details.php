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

$buttonColor = '';
$buttonText = '';

switch ($order['status']) {
    case 'Pending':
        $buttonColor = 'btn-outline-info';
        $buttonText = 'Process Order';
        break;
    case 'Processing':
        $buttonColor = 'btn-outline-success';
        $buttonText = 'Confirm Order';
        break;
    case 'Confirmed':
        $buttonColor = 'btn-success';
        $buttonText = 'Confirmed';
        break;
    case 'Cancelled':
        $buttonColor = 'btn-outline-success';
        $buttonText = 'Confirm Order';
        break;
}

$cancelButtonText = 'Cancel Order';
$cancelButtonColor = 'btn-outline-danger';

// Check the order status and update button text accordingly
switch ($order['status']) {
    case 'Processing':
    case 'Pending':
    case 'Confirmed':
        $cancelButtonText = 'Cancel Order';
        $cancelButtonColor = 'btn-outline-danger';
        break;
    case 'Cancelled':
        $cancelButtonText = 'Cancelled';
        $cancelButtonColor = 'btn-danger';
        break;
}

// Check if the order is Confirmed or Cancelled
$isConfirmed = $order['status'] === 'Confirmed';
$isCancelled = $order['status'] === 'Cancelled';
?>

<style>
    strong {
        width: 150px;
    }

    .disabled-link {
        cursor: not-allowed;
    }
</style>

<div class="container" style="margin-top: 100px;">

    <div class="my-3 d-flex align-items-center justify-content-between">
        <a href="orders.php" style="font-size: 30px;"><i class="bi bi-arrow-left-circle-fill"></i></a>
        <div>
            <?php if (isset($_SESSION['user']) && $_SESSION['user'][0]['is_admin'] === 'true') : ?>
                <a href=<?php echo "/php_ecommerce/admin/change_order_status.php?id={$order['order_id']}" ?> class="btn <?php echo $buttonColor; ?> <?php echo $isConfirmed ? 'disabled-link' : ''; ?>"><?php echo $buttonText; ?></a>
            <?php endif ?>
            <a href=<?php echo "/php_ecommerce/cancel_order.php?id={$order['order_id']}" ?> class="btn <?php echo $cancelButtonColor; ?> <?php echo $isCancelled ? 'disabled-link' : ''; ?>"><?php echo $cancelButtonText; ?></a>
        </div>
    </div>

    <div class="border rounded p-3 pt-5">
        <h3>Order Details - Order #<?php echo $orderId; ?></h3>
        <div class="mt-3">
            <p class="d-flex"><strong>Phone Number: </strong> <span><?php echo $order['phone_number']; ?></span></p>
            <p class="d-flex"><strong>Shipping Address:</strong> <span><?php echo $order['shipping_address']; ?></span></p>
            <p class="d-flex"><strong>Total Price:</strong>NGN&nbsp;<span><?php echo number_format($order['total_price']); ?></span></p>
            <p class="d-flex"><strong>Date Ordered:</strong> <span>
                    <td><?php echo date('M d, Y', strtotime($order['date_ordered'])); ?></td>
                </span></p>
            <p class="d-flex"><strong>Status:</strong>
                <small class="<?php
                                echo $order['status'] === 'Pending' ? 'bg-warning' : ($order['status'] === 'Processing' ? 'bg-info' : ($order['status'] === 'Confirmed' ? 'bg-success' : ($order['status'] === 'Cancelled' ? 'bg-danger' : '')));
                                ?> text-light p-1 rounded">
                    <?php echo ($order['status']); ?>
                </small>
            </p>
        </div>
    </div>

    <?php if (!empty($orderItems)) : ?>
        <div class="table-responsive">
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
        </div>
    <?php else : ?>
        <p>No items found for this order.</p>
    <?php endif; ?>

</div>

<?php include('./partials/footer.php'); ?>

<script>
    // Disable links on page load if order is Confirmed or Cancelled
    window.onload = function() {
        <?php if ($isConfirmed || $isCancelled) : ?>
            disableLinks();
        <?php endif; ?>
    };

    function disableLinks() {
        var confirmLink = document.querySelector('.btn-success');
        var cancelLink = document.querySelector('.btn-danger');

        if (confirmLink) {
            confirmLink.classList.add('disabled-link');
            confirmLink.onclick = function() {
                return false;
            };
        }

        if (cancelLink) {
            cancelLink.classList.add('disabled-link');
            cancelLink.onclick = function() {
                return false;
            };
        }
    }
</script>