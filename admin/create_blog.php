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

$title =  $content = $image = $Err = '';

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

                    $currentDateTime = date('Y-m-d H:i:s');

                    $sql = "INSERT INTO blogs (blog_title, blog_content, blog_image, created_at) VALUES ('$title', '$content', '$imageData', '$currentDateTime')";

                    if (mysqli_query($conn, $sql)) {
                        $message = 'Blog Added Successfully';
                        redirectWithMessage($message);
                        exit();
                    } else {
                        echo "Error uploading blog: " . mysqli_error($conn);
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

<div class="container d-flex" style="margin-top: 100px;">
    <div class="profile-left"><?php include('../partials/sidebar.php') ?></div>
    <form action='' class='flex-2 border rounded p-3 pt-5 ms-3 form-style' method='POST' style="flex: 3;" method=" post" enctype="multipart/form-data">
        <?php if ($Err) : ?>
            <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $Err ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php endif ?>
        <h4 class=" mb-3">CREATE A BLOG POST</h4>
        <div class="form-group mb-3">
            <label class="mb-2" for="title">Blog Title</label>
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

    <!-- </div> -->
</div>

<?php include('../partials/footer.php'); ?>