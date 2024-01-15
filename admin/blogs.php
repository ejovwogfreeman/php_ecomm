<?php

include('admincheck.php');
include('../config/db.php');
include('../partials/header.php');

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user'][0]['user_id'];
    $user = $_SESSION['user'][0];
    $username = $user['username'];

    // Fetch all users of the user from the database
    $sql = "SELECT * FROM blogs ORDER BY blog_id DESC";
    $result = mysqli_query($conn, $sql);
    $blogs = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $counter = 1;
}
?>

<style>
    small a {
        padding: 1px 3px !important;
        font-size: 12px !important;
    }
</style>

<!-- <div class="container" style="margin-top: 100px;"> -->
<div class="container d-flex" style="margin-top: 100px;">
    <div class="profile-left"><?php include('../partials/sidebar.php') ?></div>
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
        <h3 class="mb-3">All Blogs</h3>
        <?php if (!empty($blogs)) : ?>
            <div class="table-responsive">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th scope="col">S/N</th>
                            <th scope="col">Blog Title</th>
                            <th scope="col">Date Uploaded</th>
                            <th scope="col">View Blog</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blogs as $blog) : ?>
                            <tr>
                                <th scope="row"><?php echo $counter++ ?></th>
                                <td><?php echo $blog['blog_title']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></td>
                                <td><small><a href="/php_ecommerce/blog.php?id=<?php echo $blog['blog_id']; ?>" class="btn btn-outline-info">View Blog</a></small></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p class="mt-3">No blogs found in order history.</p>
        <?php endif; ?>
    </div>
</div>
<?php include('../partials/footer.php'); ?>