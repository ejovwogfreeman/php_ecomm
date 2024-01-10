<?php

ob_start();
include('admincheck.php');
include('../config/db.php');
include('../partials/header.php');


function redirectWithMessage($message)
{
    header('Location: /php_ecommerce/index.php?message=' . urlencode($message));
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM blogs WHERE blog_id = '$id'";

$sql_query = mysqli_query($conn, $sql);

$blog = mysqli_fetch_assoc($sql_query);

if (isset($blog['blog_title'])) {
    $title = $blog['blog_title'];
}

if (isset($blog['blog_content'])) {
    $content = $blog['blog_content'];
}

$Err = '';

if (isset($_POST['submit'])) {
    if (empty($_POST['title'])) {
        $Err = 'PLEASE ADD A BLOG TITLE';
    } else {
        $title = htmlspecialchars($_POST['title']);
        if (empty($_POST['content'])) {
            $Err = 'PLEASE ADD BLOG CONTENT';
        } else {
            $content = htmlspecialchars($_POST['content']);
            if (empty($_FILES['image']['name'])) {
                $Err = 'PLEASE ADD A BLOG IMAGE';
            } else {
                // Image handling
                $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];


                if (in_array($imageFileType, $allowedExtensions)) {
                    $image = $_FILES['image']['tmp_name'];
                    $imageData = file_get_contents($image);
                    $imageData =  mysqli_real_escape_string($conn, $imageData);

                    $blogId = $_POST['id'];

                    $sql = "UPDATE blogs SET blog_title='$title', blog_content='$content', blog_image='$imageData' WHERE blog_id='$blogId'";

                    if (mysqli_query($conn, $sql)) {
                        $message = 'Blog Updated Successfully';
                        redirectWithMessage($message);
                        exit();
                    } else {
                        echo "Error updating blog: " . mysqli_error($conn);
                    }
                } else {
                    echo "Invalid file type. Allowed types: " . implode(', ', $allowedExtensions);
                }
            }
        }
    }
}
ob_end_flush();

?>

<div class="container" style="margin-top: 100px;">

    <form action="update_blog.php" class='border rounded p-3 pt-5 mt-5 m-auto form-style' method="POST" enctype="multipart/form-data">
        <?php if ($Err) : ?>
            <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $Err ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php endif ?>
        <h4 class=" mb-3">UPDATE BLOG</h4>
        <input type="text" class="form-control" name="id" id="id" value="<?php echo $id ?>" hidden>
        <div class="form-group mb-3">
            <label class="mb-2" for="title">Blog Title:</label>
            <input type="text" class="form-control" name="title" id="title" value="<?php echo $title ?>">
        </div>

        <div class="form-group mb-3">
            <label class="mb-2" for="content">Blog Content:</label>
            <textarea class="form-control" name="content" id="content" rows="4"><?php echo $content ?></textarea>
        </div>

        <div class="form-group mb-3">
            <label class="mb-2" for="image">Select Image (PNG, JPG, JPEG, WebP):</label> <br>
            <input type="file" class="form-control-file border rounded p-2" name="image" id="image" accept="image/png, image/jpeg, image/webp" style="width: 100% ">
        </div>

        <button type="submit" class="btn btn-primary" name="submit">Upload Blog Post</button>
    </form>

</div>

<?php include('../partials/footer.php'); ?>