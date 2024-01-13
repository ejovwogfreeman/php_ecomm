<?php

include('./config/session.php');
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user'][0]['user_id'];

    // Fetch all orders of the user from the database
    $sql = "SELECT * FROM orders WHERE user_id = $userId ORDER BY date_ordered DESC";
    $result = mysqli_query($conn, $sql);
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // var_dump($orders);

    $counter = 1;
}
?>

<div class="container d-flex" style="margin-top: 100px;">
    <div class="profile-left"><?php include('./partials/sidebar.php') ?></div>
    <div class='border rounded p-3 pt-5 ms-3 profile' style="flex: 3;" style="overflow-x: scroll;">
        <?php
        if (isset($_SESSION['user']) && $_SESSION['user'][0]['is_admin'] === 'true') :
        ?>
            <h3 class="mb-3">Welcome <?php echo $_SESSION['user'][0]['username'] ?> (admin panel)!</h3>
        <?php elseif (isset($_SESSION['user'])) : ?>
            <h3 class="mb-3">Welcome <?php echo $_SESSION['user'][0]['username'] ?></h3>
        <?php endif; ?>

    </div>
</div>

<?php include('./partials/footer.php'); ?>