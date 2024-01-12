<?php

ob_start();
include('./config/db.php');
include('./partials/header.php');
include 'mail.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
}

$emailSubject = 'WELCOME ON BOARD';
$htmlFilePath = './html_mails/register.html';

$firstName = $lastName = $email = $password = $confirmPassword = $Err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['firstName'])) {
        $Err = 'PLEASE ENTER FIRST NAME';
    } else {
        $firstName = htmlspecialchars($_POST['firstName']);
        if (empty($_POST['lastName'])) {
            $Err = 'PLEASE ENTER LAST NAME';
        } else {
            $lastName = htmlspecialchars($_POST['lastName']);
            if (empty($_POST['email'])) {
                $Err = 'PLEASE ENTER EMAIL';
            } else {
                $email = htmlspecialchars($_POST['email']);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $Err = 'PLEASE ENTER A VALID EMAIL';
                } else {
                    if (empty($_POST['password'])) {
                        $Err = 'PLEASE ENTER A PASSWORD';
                    } else {
                        $password = $_POST['password'];
                        if (strlen($password) < 8) {
                            $Err = 'PASSWORD MUST BE ATLEAST 8 CHARACTERS LONG';
                        } else {
                            if (empty($_POST['confirmPassword'])) {
                                $Err = 'PASSWORD ENTER PASSWORD CONFIRMATION';
                            } else {
                                $confirmPassword = $_POST['confirmPassword'];
                                if ($password !== $confirmPassword) {
                                    $Err = 'PASSWORDS DO NOT MATCH';
                                } else {
                                    $encrypted_password = hash('md5', $password);

                                    $username = explode('@', $email)[0];

                                    $check_email = "SELECT * FROM users WHERE email = '$email'";

                                    $check_email_query = mysqli_query($conn, $check_email);

                                    $currentDateTime = date('Y-m-d H:i:s');

                                    if (mysqli_num_rows($check_email_query) > 0) {
                                        $Err = "A USER WITH THIS EMAIL ALREADY EXISTS";
                                    } else {
                                        $sql = "INSERT INTO users (username, first_name, last_name, password, email, is_admin, date_joined) values ('$username', '$firstName', '$lastName', '$encrypted_password', '$email', 'false', '$currentDateTime')";

                                        $sql_query = mysqli_query($conn, $sql);

                                        if ($sql_query) {
                                            $message = 'Account Created for ' . $username . ' Successfully!';
                                            // sendEmail($email, $emailSubject, $htmlFilePath);
                                            sendEmail($email, $emailSubject, $htmlFilePath, $email);
                                            header('Location: login.php?message=' . urldecode($message));
                                        }
                                    }
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
        <h4 class="mb-3">REGISTER TO START SHOPPING</h4>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">First Name</label>
            <div class="input-group flex-nowrap mb-4">
                <span class="input-group-text" id="addon-wrapping"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your firstname" name='firstName' value="<?php echo $firstName ?>">
            </div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Last Name</label>
            <div class="input-group flex-nowrap mb-4">
                <span class="input-group-text" id="addon-wrapping"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your lastname" name='lastName' value="<?php echo $lastName ?>">
            </div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Email</label>
            <div class="input-group flex-nowrap mb-4">
                <span class="input-group-text" id="addon-wrapping"><i class="bi bi-envelope"></i></span>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your email" name='email' value="<?php echo $email ?>">
            </div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Password</label>
            <div class="input-group flex-nowrap mb-4">
                <span class="input-group-text" id="addon-wrapping"><i class="bi bi-key"></i></span>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your password" name='password' value="<?php echo $password ?>">
            </div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Comnfirm Password</label>
            <div class="input-group flex-nowrap mb-4">
                <span class="input-group-text" id="addon-wrapping"><i class="bi bi-key"></i></span>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter password again" name='confirmPassword' value="<?php echo $confirmPassword ?>">
            </div>
        </div>
        <button class='btn btn-primary mb-2'>REGISTER</button>
        <p class="mb-0">Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>

<?php include('./partials/footer.php'); ?>