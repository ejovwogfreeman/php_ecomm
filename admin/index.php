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

<!-- <div class="container" style="margin-top: 100px;"> -->
<div class="container d-flex" style="margin-top: 100px;">
    <div class="profile-left"><?php include('../partials/sidebar.php') ?></div>
    <div class='border rounded p-3 pt-5 ms-3 profile' style="flex: 3;" style="overflow-x: scroll;">
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
        <h3 class="mb-3">All Orders</h3>
        <?php if (!empty($orders)) : ?>
            <?php
            // Group orders by month
            $groupedOrders = [];
            foreach ($orders as $order) {
                $month = date('F Y', strtotime($order['date_ordered']));
                $groupedOrders[$month][] = $order;
            }
            ?>

            <?php foreach ($groupedOrders as $month => $monthOrders) : ?>
                <h4><?php echo $month; ?></h4>
                <div class="table-responsive">
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th scope="col">S/N</th>
                                <th scope="col">Shipping Address</th>
                                <th scope="col">Total Price (NGN)</th>
                                <th scope="col">Date Ordered</th>
                                <th scope="col">Status</th>
                                <th scope="col">Order Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Reset counter at the start of each month
                            $counter = 1;
                            ?>
                            <?php foreach ($monthOrders as $order) : ?>
                                <tr>
                                    <th scope="row"><?php echo $counter++ ?></th>
                                    <td><?php echo $order['shipping_address']; ?></td>
                                    <td><?php echo number_format($order['total_price']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($order['date_ordered'])); ?></td>
                                    <td>
                                        <small class="<?php
                                                        echo $order['status'] === 'Pending' ? 'bg-warning' : ($order['status'] === 'Processing' ? 'bg-info' : ($order['status'] === 'Confirmed' ? 'bg-success' : ($order['status'] === 'Cancelled' ? 'bg-danger' : '')));
                                                        ?> text-light p-1 rounded">
                                            <?php echo ($order['status']); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small class="bg-primary text-light p-1 rounded">
                                            <a href=<?php echo "order_details.php?id={$order['order_id']}" ?> class="text-decoration-none text-light">View Order</a>
                                        </small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>


        <?php else : ?>
            <p class="mt-3">No orders found in Order history.</p>
        <?php endif; ?>

    </div>
</div>
<?php include('../partials/footer.php'); ?>