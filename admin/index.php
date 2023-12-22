<?php

include('admincheck.php');
include('../config/db.php');
include('../partials/header.php');

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user'][0]['user_id'];
    $user = $_SESSION['user'][0];
    $username = $user['username'];

    // Fetch all orders of the user from the database
    $sql = "SELECT * FROM orders ORDER BY date_ordered DESC";
    $result = mysqli_query($conn, $sql);
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $counter = 1;
}
?>

<div class="container" style="margin-top: 100px;">
    <?php if (isset($_GET['message']) && (strstr($_GET['message'], "Successfully"))  || (isset($_GET['message']) && (strstr($_GET['message'], "SUCCESSFUL")))) : ?>
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
    <div class="my-3">
        <?php if (isset($_SESSION['user'])) :  ?>
            <h3>Welcome <?php echo $username ?> (admin pannel)!</h3>
        <?php endif ?>
    </div>
    <?php if (!empty($orders)) : ?>
        <table class="table text-center">
            <thead>
                <tr>
                    <th scope="col">S/N</th>
                    <th scope="col">Shipping Address</th>
                    <th scope="col">Total Price (NGN)</th>
                    <th scope="col">Date Ordered</th>
                    <th scope="col">Status</th>
                    <th scope="col">Transaction Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) : ?>
                    <tr>
                        <th scope="row"><?php echo $counter++ ?></th>
                        <td><?php echo $order['shipping_address']; ?></td>
                        <td><?php echo number_format($order['total_price']); ?></td>
                        <td><?php echo $order['date_ordered']; ?></td>
                        <td>
                            <small class="<?php echo $order['status'] === 'Processing' ? 'bg-warning' : 'bg-success'; ?> text-light p-1 rounded">
                                <?php echo ($order['status']); ?>
                            </small>
                        </td>
                        <td><small class="bg-warning text-light p-1 rounded"> <a href=<?php echo "/php_ecommerce/order_details.php?id={$order['order_id']}" ?> class="text-decoration-none text-light">View Order</a></small></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No orders found in your order history.</p>
    <?php endif; ?>
</div>

<?php include('../partials/footer.php'); ?>