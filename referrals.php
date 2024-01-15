<?php
include('./config/session.php');
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user'][0]['user_id'];
    $username = $_SESSION['user'][0]['username'];

    // Fetch all orders of the user from the database
    $sql = "SELECT * FROM referrals WHERE referrer_code = '$username'";
    $result = mysqli_query($conn, $sql);
    $referrals = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // var_dump($orders);

    $counter = 1;
}
?>

<div class="container d-flex" style="margin-top: 100px;">
    <div class="profile-left"><?php include('./partials/sidebar.php') ?></div>
    <div class='border rounded p-3 pt-5 ms-3 profile' style="flex: 3;" style="overflow-x: scroll;">
        <?php if (isset($_GET['message']) && (strstr($_GET['message'], "Successfully"))  || (isset($_GET['message']) && (strstr($_GET['message'], "SUCCESSFUL")))) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?php echo $_GET['message'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php elseif (isset($_GET['message'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $_GET['message'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php endif ?>
        <h3 class="mb-3">Your Referrals</h3>
        <?php if (!empty($referrals)) : ?>
            <?php
            // Group orders by month
            $groupedReferrals = [];
            foreach ($referrals as $referral) {
                $month = date('F Y', strtotime($referral['referral_date']));
                $groupedReferrals[$month][] = $referral;
            }
            ?>

            <?php foreach ($groupedReferrals as $month => $monthReferrals) : ?>
                <h4><?php echo $month; ?></h4>
                <div class="table-responsive">
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th scope="col">S/N</th>
                                <th scope="col">Referrer Code</th>
                                <th scope="col">Referee Username</th>
                                <th scope="col">Referral Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Reset counter at the start of each month
                            $counter = 1;
                            ?>
                            <?php foreach ($monthReferrals as $referral) : ?>
                                <tr>
                                    <th scope="row"><?php echo $counter++ ?></th>
                                    <td><?php echo $referral['referrer_code']; ?></td>
                                    <td><?php echo $referral['referee_username']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($referral['referral_date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>


        <?php else : ?>
            <p class="mt-3">No completed orders found in your order history.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('./partials/footer.php'); ?>