<?php

ob_start();
session_start();
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
}

$user = $oldPassword = $newPassword = $confirmPassword = $Err = '';

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $email = mysqli_real_escape_string($conn, $email);

    $sql_email = "SELECT * FROM users WHERE email = '$email'";

    $sql_email_query = mysqli_query($conn, $sql_email);

    if ($sql_email_query) {

        if (mysqli_num_rows($sql_email_query) > 0) {

            $user = mysqli_fetch_assoc($sql_email_query);

            $id = $user['user_id'];

            $encryptedPassword = $user['password'];
        } else {
            $Err = "A USER WITH THIS EMAIL DOES NOT EXIST";
        }

        mysqli_free_result($sql_email_query);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_GET['email'])) {
        $Err = 'NO EMAIL GIVEN';
    } else {
        if (empty($_POST['newPassword'])) {
            $Err = 'PLEASE ENTER YOUR NEW PASSWORD';
        } else {
            $newPassword = $_POST['newPassword'];
            if (strlen($newPassword) < 8) {
                $Err = 'PASSWORD MUST BE AT LEAST 8 CHARACTERS LONG';
            } else {
                $encrypted_password = hash('md5', $newPassword);
                if ($encrypted_password === $encryptedPassword) {
                    $Err = 'YOUR NEW PASSWORD CANNOT BE THESAME AS YOUR OLD PASSORD';
                } else {
                    if (empty($_POST['confirmPassword'])) {
                        $Err = 'PASSWORD ENTER PASSWORD CONFIRMATION';
                    } else {
                        $confirmPassword = $_POST['confirmPassword'];
                        if ($newPassword !== $confirmPassword) {
                            $Err = 'PASSWORDS DO NOT MATCH';
                        } else {

                            $userId = $_POST['id'];

                            $sql = "UPDATE users SET password = '$encrypted_password' WHERE user_id = '$userId'";

                            $sql_query = mysqli_query($conn, $sql);

                            if ($sql_query) {

                                unset($_SESSION['user']);

                                $message = 'Password Updated Successfully, Login with your new password to continue shopping!';

                                header('Location: login.php?message=' . urldecode($message));
                                exit();
                            } else {
                                $Err = 'Error updating password: ' . mysqli_error($conn);
                            }
                        }
                    }
                }
            }
        }
    }
}
ob_end_flush();

?>

<div class="container" style="margin-top: 100px;">
    <form action='' class='border rounded p-3 pt-5 mt-5 m-auto form-style' method='POST'>
        <?php if ($Err) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $Err ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php endif ?>
        <h4 class="mb-3">RESET PASSWORD</h4>
        <input type="text" class="form-control" name="id" id="id" value="<?php echo $id ?>" hidden>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">New Password</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your password" name='newPassword' value="<?php echo $newPassword ?>">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Comnfirm New Password</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter password again" name='confirmPassword' value="<?php echo $confirmPassword ?>">
        </div>
        <button class='btn btn-primary mb-2'>RESET PASSWORD</button>
    </form>
</div>
<?php include('./partials/footer.php'); ?>