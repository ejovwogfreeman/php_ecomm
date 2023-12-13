<?php include('./partials/header.php');

$email = $password = $emailErr = $passwordErr = '';

?>

<div class="container">
    <form action="" class='border rounded p-3 mt-5 m-auto' style='width: 60%' method='POST'>

        <?php if ($emailErr) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $emailErr ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php elseif ($passwordErr) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $passwordErr ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php endif ?>

        <h4>LOGIN TO CONTINUE SHOPPING</h4>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Email</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your email" name='title' value="<?php echo $email ?>">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Password</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your password" name='title' value="<?php echo $password ?>">
        </div>
        <button class='btn btn-primary'>LOGIN</button>
        <p>New here? <a href="register.php">create an account</a></p>
    </form>
</div>

<?php include('./partials/footer.php'); ?>