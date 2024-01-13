<?php

ob_start();
include('./config/session.php');
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    if ($_SESSION['user'][0]['is_admin'] === 'true') {
        // Check if an 'id' parameter is provided in the URL
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $userId = $_GET['id'];

            // Fetch user details based on the provided user ID
            $sql = "SELECT * FROM users WHERE user_id = $userId";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                // Check if a user with the specified ID exists
                if (mysqli_num_rows($result) > 0) {
                    $user = mysqli_fetch_assoc($result);
                } else {
                    $errorMessage = urlencode("User with this id does not exist");
                    header('Location: /php_ecommerce/admin/users.php?message=' . $errorMessage);
                    exit();
                }
            } else {
                // Handle the case where the query was not executed successfully
                // You might want to log the error or display a generic error message
                $errorMessage = urlencode("Error fetching user details");
                header('Location: /php_ecommerce/admin/users.php?message=' . $errorMessage);
                exit();
            }
        }
    } else {
        // User is not an admin, fetch details from session
        $user = $_SESSION['user'][0];
    }

    // Extract user details
    $firstName = $user['first_name'];
    $lastName = $user['last_name'];
    $userName = $user['username'];
    $email = $user['email'];
    $phoneNumber = $user['phone_number'];
    $address = $user['address'];
}

if (isset($_SESSION['user'])) {
    // Assuming the user ID of the logged-in user is stored in $loggedInUserId
    $loggedInUserId = $_SESSION['user'][0]['user_id'];

    if ($_SESSION['user'][0]['is_admin'] === 'true' && isset($_GET['id']) && is_numeric($_GET['id'])) {
        // If the logged-in user is an admin and 'id' parameter is provided in the URL
        $profileUserId = $_GET['id'];
    } else {
        // If the logged-in user is not an admin or 'id' parameter is not provided, use the logged-in user's ID
        $profileUserId = $loggedInUserId;
    }

    // Check if the logged-in user is the owner of the profile
    $headerText = ($loggedInUserId == $profileUserId) ? "YOUR PROFILE" : "$userName's Profile";
}

ob_end_flush();

?>

<div class="container d-flex" style="margin-top: 100px;">
    <div class="profile-left"><?php include('./partials/sidebar.php') ?></div>
    <div class='border rounded p-3 pt-5 ms-3 profile' style="flex: 3;">
        <h3 class="mb-3 text-center"><?php echo $headerText; ?></h3>
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