<?php

ob_start();
include('./config/session.php');
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
    $id = $user['user_id'];
    $encryptedPassword = $user['password'];
}

$oldPassword = $newPassword = $confirmPassword = $Err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['oldPassword'])) {
        $Err = 'PLEASE ENTER OLD PASSWORD';
    } else {
        $oldPassword = htmlspecialchars($_POST['oldPassword']);
        $encrypted = hash('md5', $oldPassword);
        if ($encrypted !== $encryptedPassword) {
            $Err = 'PASSWORD IS NOT THE SAME AS THE OLD PASSWORD';
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
                                    // Update session information
                                    $_SESSION['user'][0]['password'] = $encrypted_password;

                                    $message = 'Password Updated Successfully!';

                                    header('Location: index.php?message=' . urldecode($message));
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
}
ob_end_flush();

?>

<div class="container d-flex" style="margin-top: 100px;">
    <div class="profile-left"><?php include('./partials/sidebar.php') ?></div>
    <form action='' class='border rounded p-3 pt-5 ms-3 form-style' method='POST' style="flex: 3;">
        <?php if ($Err) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $Err ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php endif ?>
        <h4 class="mb-3">CHANGE PASSWORD</h4>
        <input type="text" class="form-control" name="id" id="id" value="<?php echo $id ?>" hidden>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Old Password</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your password" name='oldPassword' value="<?php echo $oldPassword ?>">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">New Password</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your password" name='newPassword' value="<?php echo $newPassword ?>">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Comnfirm New Password</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter password again" name='confirmPassword' value="<?php echo $confirmPassword ?>">
        </div>
        <button class='btn btn-primary mb-2'>CHANGE PASSWORD</button>
    </form>
</div>
<?php include('./partials/footer.php'); ?>