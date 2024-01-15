<?php

include('admincheck.php');
include('../config/db.php');
include('../partials/header.php');

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user'][0]['user_id'];
    $user = $_SESSION['user'][0];
    $username = $user['username'];

    // Fetch all users of the user from the database
    $sql = "SELECT * FROM products ORDER BY product_id DESC";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $counter = 1;
}
?>

<style>
    small a {
        padding: 1px 3px !important;
        font-size: 12px !important;
    }
</style>

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
        <h3 class="mb-3">All Products</h3>
        <?php if (!empty($products)) : ?>
            <div class="table-responsive">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th scope="col">S/N</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Product Category</th>
                            <th scope="col">Product Price</th>
                            <th scope="col">Date Uploaded</th>
                            <th scope="col">View Profile</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <th scope="row"><?php echo $counter++ ?></th>
                                <td><?php echo $product['product_name']; ?></td>
                                <td><?php echo $product['product_category']; ?></td>
                                <td><?php echo $product['product_price']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($product['created_at'])); ?></td>
                                <td><small><a href="/php_ecommerce/product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-outline-info">View Product</a></small></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p class="mt-3">No products found in order history.</p>
        <?php endif; ?>
    </div>
</div>
<?php include('../partials/footer.php'); ?>