<?php
session_start();
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
    $username = $user['username'];
}
?>

<div class="container mt-3">
    <?php if ((isset($_SESSION['user'])) && (isset($_GET['message']))) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?php echo $_GET['message'] ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </div>
    <?php endif ?>
    <div class="mt-3">
        <h3>404 page not found.</h3>
    </div>
</div>

<?php include('./partials/footer.php'); ?>