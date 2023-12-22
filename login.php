<?php

session_start();
include('./config/db.php');
include('./partials/header.php');

$email = $password  = $Err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['email'])) {
        $Err = 'PLEASE ENTER YOUR EMAIL';
    } else {
        $email = htmlspecialchars($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $Err = 'PLEASE ENTER A VALID EMAIL';
        } else {
            if (empty($_POST['password'])) {
                $Err = 'PLEASE ENTER YOUR PASSWORD';
            } else {
                $password = $_POST['password'];
                if (strlen($password) < 8) {
                    $Err = 'PASSWORD MUST BE ATLEAST 8 CHARACTERS LONG';
                } else {
                    $encrypted_password = hash('md5', $password);

                    $check_email = "SELECT * FROM users WHERE email = '$email'";

                    $check_email_query = mysqli_query($conn, $check_email);

                    if (mysqli_num_rows($check_email_query) > 0) {

                        $check_password = "SELECT * FROM users WHERE password = '$encrypted_password'";

                        $check_password_query = mysqli_query($conn, $check_password);

                        if (mysqli_num_rows($check_password_query) > 0) {

                            $user = mysqli_fetch_all($check_email_query, MYSQLI_ASSOC);

                            $_SESSION['user'] = $user;

                            $message = $user[0]['username'] . " LOGGED IN SUCCESSFUL";

                            header('Location: index.php?message=' . urldecode($message));
                        } else {
                            $Err = "INCORRECT PASSWORD";
                        }
                    } else {
                        $Err = "A USER WITH THIS EMAIL DOES NOT EXIST";
                    }
                }
            }
        }
    }
}




?>

<div class="container" style="margin-top: 100px;">
    <?php if (isset($_GET['message'])) : ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <strong><?php echo $_GET['message'] ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </div>
    <?php endif ?>
    <form action="" class='border rounded p-3 mt-5 m-auto form-style' method='POST'>
        <?php if ($Err) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $Err ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php endif ?>
        <h4>LOGIN TO CONTINUE SHOPPING</h4>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Email</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your email" name='email' value="<?php echo $email ?>">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Password</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your password" name='password' value="<?php echo $password ?>">
        </div>
        <button class='btn btn-primary mb-2'>LOGIN</button>
        <p>New here? <a href="register.php">create an account</a></p>
    </form>
</div>

<?php include('./partials/footer.php'); ?>