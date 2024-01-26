<?php
// Check if the form is submitted
$subscription_email = $subscription_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming your form has an input named 'email'
    $subscription_email = isset($_POST['subscription_email']) ? trim($_POST['subscription_email']) : '';

    // Validate email address
    if (empty($subscription_email)) {
        $subscription_email = '';
    } else if (!filter_var($subscription_email, FILTER_VALIDATE_EMAIL)) {
        $subscription_message = 'Invalid email address';
    } else {
        if ($conn) {
            // Check if the user exists in the users table
            $userQuery = "SELECT first_name, last_name FROM users WHERE email = '$subscription_email'";
            $userResult = mysqli_query($conn, $userQuery);

            if ($userResult) {
                $userDetails = mysqli_fetch_assoc($userResult);
                $firstName = $userDetails ? $userDetails['first_name'] : ''; // Use ' ' if user doesn't exist
                $lastName = $userDetails ? $userDetails['last_name'] : ''; // Use ' ' if user doesn't exist

                // Check if the email is already subscribed
                $checkQuery = "SELECT * FROM newsletters WHERE email = '$subscription_email'";
                $checkResult = mysqli_query($conn, $checkQuery);

                if ($checkResult) {
                    if (mysqli_num_rows($checkResult) > 0) {
                        // Email already subscribed
                        $subscription_message = 'Email already subscribed.';
                    } else {
                        // Email not subscribed, insert into the newsletters table
                        $insertQuery = "INSERT INTO newsletters (email, first_name, last_name, date_subscribed) 
                                        VALUES ('$subscription_email', '$firstName', '$lastName', NOW())";

                        $insertResult = mysqli_query($conn, $insertQuery);

                        if ($insertResult) {
                            $subscription_message = 'Subscription has been done Successfully!';
                            $subscription_email = ''; // Clear email field after successful subscription
                        } else {
                            // Debugging statement
                            echo "Insert Error: " . mysqli_error($conn);

                            $subscription_message = 'Subscription failed. Please try again later.';
                        }
                    }
                } else {
                    // Debugging statement
                    echo "Check Query Error: " . mysqli_error($conn);

                    $subscription_message = 'Error checking subscription status. Please try again later.';
                }
            } else {
                // Debugging statement
                echo "User Query Error: " . mysqli_error($conn);

                $subscription_message = 'Error checking user status. Please try again later.';
            }

            // Close the database connection
            mysqli_close($conn);
        } else {
            $subscription_message = 'Error connecting to the database. Please try again later.';
        }
    }
}
?>



<style>
    .input {
        width: 50%;

        @media (max-width: 340px) {
            width: 45%;
        }
    }

    .input:focus {
        outline: none;
    }
</style>

<?Php
$isIndexPage = basename($_SERVER['PHP_SELF']) == 'index.php';
$isAdminPage = strpos($_SERVER['PHP_SELF'], 'admin') !== false;

$marginTopClass = $isIndexPage && !$isAdminPage ? 'mt-0' : 'mt-4';
?>

<footer class='text-center <?php echo $marginTopClass; ?> py-3 bg-dark text-light'>
    <div class="container pt-5 pb-3 inner-footer">
        <ul class="text-start">
            <h5 class="mb-2">Categories</h5>
            <li><a class="text-light" href="/php_ecommerce/categories.php?category=electronics">Electronics</a></li>
            <li><a class="text-light" href="/php_ecommerce/categories.php?category=laptops">Laptops</a></li>
            <li><a class="text-light" href="/php_ecommerce/categories.php?category=accessories">Accessories</a></li>
            <li><a class="text-light" href="/php_ecommerce/categories.php?category=phones">Phones</a></li>
            <li><a class="text-light" href="/php_ecommerce/exclusive_deals.php">Phones</a></li>
        </ul>
        <ul class="text-start">
            <h5 class="mb-2">Customer Service</h5>
            <li><a class="text-light" href="">Contact Us</a></li>
            <li><a class="text-light" href="">Return Policy</a></li>
            <li><a class="text-light" href="">Give Us Feedback</a></li>
        </ul>
        <ul class="text-start">
            <h5 class="mb-2">About Us</h5>
            <li><a class="text-light" href="">About</a></li>
            <li><a class="text-light" href="">Location</a></li>
            <li><a class="text-light" href="">FAQ</a></li>
            <li><a class="text-light" href="">Affiliate</a></li>
            <li><a class="text-light" href="">Contact Us</a></li>
        </ul>
        <ul class="text-start">
            <h5 class="mb-2">Follow Us</h5>
            <!-- <li><a class="text-light" href=""><i class="bi bi-facebook"></i><span class="ms-2">Facebook</span></a></li> -->
            <li><a class="text-light" href=""><i class="bi bi-instagram"></i><span class="ms-2">Instagram</span></a></li>
            <li><a class="text-light" href=""><i class="bi bi-twitter"></i><span class="ms-2">Twitter</span></a></li>
            <li><a class="text-light" href=""><i class="bi bi-youtube"></i><span class="ms-2">Youtube</span></a></li>
        </ul>
        <ul class="text-start">
            <?php if (isset($subscription_message) && (strstr($subscription_message, "Successfully") || strstr($subscription_message, "SUCCESSFUL")) && $subscription_message !== '') : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><?php echo $subscription_message ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
            <?php elseif (isset($subscription_message) && $subscription_message !== '') : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?php echo $subscription_message ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
            <?php endif ?>
            <h5 class="mb-2">Subscribe to our Newsletter</h5>
            <form action="" method="POST">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="text" class="bg-transparent px-2 input" placeholder="example@gmail.com" aria-label="email" name="subscription_email" value="<?php echo $subscription_email ?>" aria-describedby="basic-addon1" style="border: 1px solid white; color: white;">
                    <button class="btn" type="submit" style="border: 1px solid white; color: white">SUBSCRIBE</button>
                </div>
            </form>
        </ul>
    </div>
    <p class="m-0">copyright &copy; <?php echo date('Y') ?> Tech360</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>