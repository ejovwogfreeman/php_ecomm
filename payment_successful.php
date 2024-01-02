<?php

session_start();
include('./config/db.php');
include('./partials/header.php');

?>

<div class="container text-center" style="margin-top: 100px;">
    <h3>Payment Successful</h3>
    <img src="images/check.gif" alt="" class="d-block m-auto mt-4 mb-3">
    <a href="dashboard.php" class="btn btn-outline-success">View your orders</a>
</div>

<?php include('./partials/footer.php'); ?>