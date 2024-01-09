<?php

include('./config/session.php');
include('./config/db.php');
include('./partials/header.php');


if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
    $firstName = $user['first_name'];
    $lastName = $user['last_name'];
    $phoneNumber = $user['phone_number'];
    $address = $user['address'];
}

$Err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['firstName'])) {
        $Err = 'PLEASE ENTER FIRST NAME';
    } else {
        $firstName = htmlspecialchars($_POST['firstName']);
        if (empty($_POST['lastName'])) {
            $Err = 'PLEASE ENTER LAST NAME';
        } else {
            $lastName = htmlspecialchars($_POST['lastName']);
            if (empty($_POST['phoneNumber'])) {
                $Err = 'PLEASE ENTER A PHONE NUMBER';
            } else {
                $phoneNumber = htmlspecialchars($_POST['phoneNumber']);
                if (empty($_POST['address'])) {
                    $Err = "PLEASE ENTER AN ADDRESS";
                } else {
                    $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];

                    if (in_array($imageFileType, $allowedExtensions)) {
                        $image = $_FILES['image']['tmp_name'];
                        $imageData = file_get_contents($image);
                        $imageData =  mysqli_real_escape_string($conn, $imageData);

                        $sql = "UPDATE users SET first_name = '$firstName', last_name = '$lastName', phone_number='$phoneNumber', address='$address', profile_picture='$imageData' WHERE user_id = '{$user['user_id']}'";

                        if (mysqli_query($conn, $sql)) {
                            $message = 'Profile Updated Successfully';
                            redirectWithMessage($message);
                            exit();
                        } else {
                            echo "Error updating profile: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Invalid file type. Allowed types: " . implode(', ', $allowedExtensions);
                    }
                }
            }
        }
    }
}


?>


<div class="container d-flex" style="margin-top: 100px;">
    <div class="profile-left"><?php include('./partials/sidebar.php') ?></div>
    <form action='' class='flex-2 border rounded p-3 pt-5 ms-3 form-style' method='POST' style="flex: 3;">
        <?php if ($Err) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $Err ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php endif ?>
        <h4 class="mb-3">UPDATE PROFILE</h4>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">First Name</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your firstname" name='firstName' value="<?php echo $firstName ?>">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your lastname" name='lastName' value="<?php echo $lastName ?>">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your phone number" name='phoneNumber' value="<?php echo $phoneNumber ?>">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Address</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter your address" name='address' value="<?php echo $address ?>">
        </div>
        <div class="form-group mb-3">
            <label class="mb-2" for="image">Profile Picture (PNG, JPG, JPEG, WebP):</label> <br>
            <input type="file" class="form-control-file border rounded p-2" name="image" id="image" accept="image/png, image/jpeg, image/webp" style="width: 100% ">
        </div>
        <button class='btn btn-primary mb-2'>UPDATE PROFILE</button>
    </form>
</div>
<?php include('./partials/footer.php'); ?>