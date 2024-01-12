<?php

ob_start();
session_start();
include('./config/db.php');
include('./partials/header.php');
include 'mail.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
}

$emailSubject = 'RESET PASSWORD';
$htmlFilePath = './html_mails/reset_password.html';

$firstName = $lastName = $email = $password = $confirmPassword = $Err = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['email'])) {
        $Err = 'PLEASE ENTER EMAIL';
    } else {
        $email = htmlspecialchars($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $Err = 'PLEASE ENTER A VALID EMAIL';
        } else {
            $email = mysqli_real_escape_string($conn, $email);

            $sql_email = "SELECT * FROM users WHERE email = '$email'";

            $sql_email_query = mysqli_query($conn, $sql_email);

            if ($sql_email_query) {

                if (mysqli_num_rows($sql_email_query) > 0) {

                    $user = mysqli_fetch_assoc($sql_email_query);

                    sendEmail($email, $emailSubject, $htmlFilePath, $email);

                    $message = "An email has been sent to your email \"$email\" with a link to reset your password";

                    header('Location: forgot_password.php?message=' . urldecode($message));
                } else {
                    $Err = "A USER WITH THIS EMAIL DOES NOT EXIST";
                }

                mysqli_free_result($sql_email_query);
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}
ob_end_flush();

?>

<div class="container" style="margin-top: 100px;">
    <form action='' class='border rounded p-3 pt-5 mt-5 m-auto form-style' method='POST'>
        <?php if (isset($Err) && $Err !== '') : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $Err ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php elseif (isset($message) && $message !== '') : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?php echo $message ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php endif ?>

        <h4 class="mb-3">FORGOT PASSWORD</h4>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Your Email</label>
            <div class="input-group flex-nowrap mb-3">
                <span class="input-group-text" id="addon-wrapping"><i class="bi bi-envelope"></i></span>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your registered email" name='email' value="<?php echo $email ?>">
            </div>
        </div>
        <button class='btn btn-primary mb-2'>FORGOT PASSWORD</button>
        <p class="mb-0">Back to <a href="login.php">Login</a></p>
    </form>
</div>

<?php include('./partials/footer.php'); ?>