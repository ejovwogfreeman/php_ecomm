<?php

ob_start();
session_start();
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
    $user_id = $user['user_id'];
    $username = $user['username'];
}


$id = $_GET['id'];

$sql = "SELECT * FROM blogs WHERE blog_id = '$id'";

$sql_query = mysqli_query($conn, $sql);

$blog = mysqli_fetch_all($sql_query, MYSQLI_ASSOC)[0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the input data

    if ($user) {
        $blog_id = $_GET['id'];
        $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);

        // Insert the comment into the database
        $sql = "INSERT INTO blog_comments (blog_id, user_id, commenter_name, commenter_text, created_at) VALUES ('$blog_id', '$user_id', '$username', '$comment_text', NOW())";

        if (mysqli_query($conn, $sql)) {
            // Redirect to the same page after successful comment submission
            header("Location: {$_SERVER['PHP_SELF']}?id=$blog_id");
            exit();
        } else {
            echo "Error adding comment: " . mysqli_error($conn);
        }
    } else {
        header('Location: login.php');
    }
}

if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];

    // Fetch the blog details
    $blog_query = mysqli_query($conn, "SELECT * FROM blogs WHERE blog_id = $blog_id");
    $blog = mysqli_fetch_assoc($blog_query);

    // Fetch comments for the specific blog
    $comments_query = mysqli_query($conn, "SELECT * FROM blog_comments WHERE blog_id = $blog_id ORDER BY created_at DESC");
    $comments = mysqli_fetch_all($comments_query, MYSQLI_ASSOC);
}

ob_end_flush();

?>

<style>
    .img-style {
        height: 500px;
        width: 100%;
        object-fit: cover;

        @media (max-width: 991px) {
            width: 100%;
            height: auto
        }
    }
</style>

<div class="container" style="margin-top: 100px;">
    <div class="">
        <?php
        $imageData = $blog['blog_image'];
        $imageInfo = getimagesizefromstring($imageData);

        if ($imageInfo !== false) {
            $imageFormat = $imageInfo['mime'];
            $img_src = "data:$imageFormat;base64," . base64_encode($imageData);
        } else {
            echo "Unable to determine image type.";
        }
        ?>
        <img class="border rounded img-style" src="<?php echo $img_src ?>" alt="<?php echo $blog['blog_title'] ?>">
        <div class="my-4">
            <h3><?php echo ucwords($blog['blog_title']) ?></h3>
            <P class="my-3" style="text-align: justify;"><?php echo ucfirst($blog['blog_content']) ?></P>
            <?php if (isset($_SESSION['user']) && $_SESSION['user'][0]['is_admin'] === 'true') : ?>
                <div class="mt-3">
                    <a href=<?php echo "/php_ecommerce/admin/update_blog.php?id={$blog['blog_id']}" ?> class="btn btn-outline-primary me-1">UPDATE</a>
                    <a href=<?php echo "/php_ecommerce/admin/delete_blog.php?id={$blog['blog_id']}" ?> class="btn btn-outline-danger">DELETE</a>
                </div>
            <?php endif ?>
        </div>
        <form method="POST" action="">
            <h3>Leave a comment!</h3>
            <div class="d-flex align-items-center mt-3">
                <textarea name="comment_text" class="rounded border-dark p-2" style="width: 100%; height: 40px; outline: none"></textarea>
                <button type="submit" class="btn btn-primary ms-2">COMMENT</button>
            </div>
        </form>

        <div class="mt-4">
            <?php if (!empty($comments)) : ?>
                <?php foreach ($comments as $comment) : ?>
                    <div class="border shadow-sm rounded p-3 mb-3">
                        <small class="m-0"><?php echo "<strong>{$comment['commenter_name']}</strong> - {$comment['created_at']}"; ?></small>
                        <p class="m-0"><?php echo "{$comment['commenter_text']}" ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No comments found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('./partials/footer.php'); ?>