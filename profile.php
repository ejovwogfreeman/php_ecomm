<?php

include('./config/session.php');
include('./config/db.php');
include('./partials/header.php');

?>

<div class="container d-flex" style="margin-top: 100px;">
    <div class="profile-left"><?php include('./partials/sidebar.php') ?></div>
    <div class='border rounded p-3 pt-5 profile' style="flex: 3;">
        <h3 class="mb-3 text-center">YOUR PROFILE</h3>
        <img class="profile-image" src="images/default.jpg" alt="">
        <div class="mt-3">
            <strong class="d-block mt-2">First Name:</strong>
            <span class="d-block">Godbless</span>
            <hr>
            <strong class="d-block mt-2">Last Name:</strong>
            <span class="d-block">Ejovwo</span>
            <hr>
            <strong class="d-block mt-2">Email:</strong>
            <span class="d-block">freeman007@gmail.com</span>
            <hr>
            <strong class="d-block mt-2">Phone Number:</strong>
            <span class="d-block">08164471007</span>
            <hr>
            <strong class="d-block mt-2">Address:</strong>
            <span class="d-block">Ugbowo, Benin City, Edo State.</span>
        </div>
    </div>
</div>
<?php include('./partials/footer.php'); ?>