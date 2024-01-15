<?php
include('./config/session.php');
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    // Check if referral_earning_id is provided in the URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $referralId = $_GET['id'];

        // Fetch order details
        $sqlReferral = "SELECT * FROM referral_earnings WHERE referral_earning_id = '$referralId'";
        $resultReferral = mysqli_query($conn, $sqlReferral);
        $referral = mysqli_fetch_assoc($resultReferral);
        $counter = 1;
    }
}

$buttonColor = '';
$buttonText = '';

switch ($referral['status']) {
    case 'unpaid':
        $buttonColor = 'btn-outline-success';
        $buttonText = 'Pay Earning';
        break;
    case 'paid':
        $buttonColor = 'btn-success';
        $buttonText = 'Paid';
        break;
}

$isPaid = $referral['status'] === 'paid';

?>

<style>
    strong {
        width: 150px;
    }

    .disabled-link {
        cursor: not-allowed;
    }
</style>

<div class="container" style="margin-top: 100px;">

    <div class="my-3 d-flex align-items-center justify-content-between">
        <a href="referral_earnings.php" style="font-size: 30px;"><i class="bi bi-arrow-left-circle-fill"></i></a>
        <div>
            <?php if (isset($_SESSION['user']) && $_SESSION['user'][0]['is_admin'] === 'true') : ?>
                <a href=<?php echo "/php_ecommerce/admin/change_earning_status.php?id={$referral['referral_earning_id']}" ?> class="btn <?php echo $buttonColor; ?> <?php echo $isPaid ? 'disabled-link' : ''; ?>"><?php echo $buttonText; ?></a>
            <?php endif ?>
        </div>
    </div>

    <div class="border rounded p-3 pt-5">
        <h3>Order Details - Order #<?php echo $referralId; ?></h3>
        <div class="mt-3">
            <p class="d-flex"><strong>Referrar Code: </strong> <span><?php echo $referral['referrer_code']; ?></span></p>
            <p class="d-flex"><strong>Referee Username:</strong> <span><?php echo $referral['referee_username']; ?></span></p>
            <p class="d-flex"><strong>Amount:</strong>NGN&nbsp;<span><?php echo number_format($referral['amount']); ?></span></p>
            <p class="d-flex"><strong>Comission Earned:</strong>NGN&nbsp;<span><?php echo number_format($referral['comission_earned']); ?></span></p>
            <p class="d-flex">
                <strong>Description:</strong><span><?php echo $referral['description']; ?></span>
            </p>
            <p class="d-flex"><strong>Date Earned:</strong><span><?php echo date('M d, Y', strtotime($referral['date_earned'])); ?></span></p>
            </span></p>
            <p class="d-flex"><strong>Status:</strong>
                <small class="<?php
                                echo $referral['status'] === 'paid' ? 'bg-success' : 'bg-danger';
                                ?> text-light p-1 rounded">
                    <?php echo ($referral['status']); ?>
                </small>
            </p>
        </div>
    </div>

</div>

<?php include('./partials/footer.php'); ?>

<script>
    // Disable links on page load if order is Confirmed or Cancelled
    window.onload = function() {
        <?php if ($isPaid) : ?>
            disableLinks();
        <?php endif; ?>
    };

    function disableLinks() {
        var confirmLink = document.querySelector('.btn-success');
        var cancelLink = document.querySelector('.btn-danger');

        if (confirmLink) {
            confirmLink.classList.add('disabled-link');
            confirmLink.onclick = function() {
                return false;
            };
        }

        if (cancelLink) {
            cancelLink.classList.add('disabled-link');
            cancelLink.onclick = function() {
                return false;
            };
        }
    }
</script>