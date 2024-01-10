<?php
include('./config/session.php');
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
    $firstName = $user['first_name'];
    $lastName = $user['last_name'];
    $userName = $user['username'];
    $email = $user['email'];
    $phoneNumber = $user['phone_number'];
    $address = $user['address'];
}

?>

<div class="container d-flex" style="margin-top: 100px;">
    <div class="profile-left"><?php include('./partials/sidebar.php') ?></div>
    <div class='border rounded p-3 pt-5 ms-3 profile' style="flex: 3;">
        <h3 class="mb-3 text-center">YOUR PROFILE</h3>
        <?php
        $imageData = $user['profile_picture'];

        if (!empty($imageData)) {
            $imageInfo = getimagesizefromstring($imageData);

            if ($imageInfo !== false) {
                $imageFormat = $imageInfo['mime'];
                $img_src = "data:$imageFormat;base64," . base64_encode($imageData);
            } else {
                echo "Unable to determine image type.";
            }
        } else {
            // If no image is available, use the default image
            $img_src = "images/default.jpg";
        }
        ?>
        <img class="profile-image" src="<?php echo $img_src; ?>" alt="<?php echo $user['username']; ?>">
        <div class="mt-3">
            <strong class="d-block mt-2">First Name:</strong>
            <span class="d-block"><?php echo $firstName ?></span>
            <hr>
            <strong class="d-block mt-2">Last Name:</strong>
            <span class="d-block"><?php echo $lastName ?></span>
            <hr>
            <strong class="d-block mt-2">Username:</strong>
            <span class="d-block"><?php echo $userName ?></span>
            <hr>
            <strong class="d-block mt-2">Email:</strong>
            <span class="d-block"><?php echo $email ?></span>
            <hr>
            <strong class="d-block mt-2">Phone Number:</strong>
            <span class="d-block"><?php echo $phoneNumber ?></span>
            <hr>
            <strong class="d-block mt-2">Address:</strong>
            <span class="d-block"><?php echo $address ?></span>
        </div>
    </div>
</div>
<?php include('./partials/footer.php'); ?>