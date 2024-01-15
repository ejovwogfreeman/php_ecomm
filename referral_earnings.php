<?php
include('./config/session.php');
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user'][0]['user_id'];
    $username = $_SESSION['user'][0]['username'];

    // Fetch all orders of the user from the database
    $sql = "SELECT * FROM referral_earnings WHERE referrer_code = '$username'";
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
        <h3 class="mb-3">Your Referral/Affiliate Earning</h3>

        <div class="border rounded p-2 mb-3">
            <div><small class="mb-3">All Time Earning <?php echo getTotalEarningsOfUser() ?></small></div>
            <div><small class="mb-3">Paid Earning <?php echo getTotalPaidEarningsOfUser() ?></small></div>
        </div>

        <?php if (!empty($referrals)) : ?>
            <?php
            // Group orders by month
            $groupedReferrals = [];
            foreach ($referrals as $referral) {
                $month = date('F Y', strtotime($referral['date_earned']));
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
                                <th scope="col">Referee Username</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Comission Earned</th>
                                <th scope="col">Date Earned</th>
                                <th scope="col">Status</th>
                                <th scope="col">View Details</th>
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
                                    <td><?php echo $referral['referee_username']; ?></td>
                                    <td><?php echo $referral['amount']; ?></td>
                                    <td><?php echo $referral['comission_earned']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($referral['date_earned'])); ?></td>
                                    <td>
                                        <small class="<?php echo $referral['status'] === 'paid' ? 'bg-success' : 'bg-danger'; ?> text-light p-1 rounded">
                                            <?php echo ($referral['status']); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small class="bg-primary text-light p-1 rounded">
                                            <a href=<?php echo "/php_ecommerce/referral_earning.php?id={$referral['referral_earning_id']}" ?> class="text-decoration-none text-light">View Details</a>
                                        </small>
                                    </td>
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

<?php
function getTotalPaidEarningsOfUser()
{
    global $conn;
    global $userId;
    $sql = "SELECT SUM(comission_earned) AS totalEarnings FROM referral_earnings WHERE referrer_user_id = $userId AND status = 'paid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $totalEarnings = 'NGN ' . ($row['totalEarnings'] ?? 0);
    return $totalEarnings;
}

function getTotalEarningsOfUser()
{
    global $conn;
    global $userId;
    $sql = "SELECT * FROM users WHERE user_id = $userId";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return 'NGN ' . number_format($row['referral_earning']);
}
