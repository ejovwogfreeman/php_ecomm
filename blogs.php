<?php
session_start();
include('./config/db.php');
include('./partials/header.php');

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'][0];
    $username = $user['username'];
}

if (isset($_GET['category'])) {
    $category = $_GET['category'];
}

// Check if the search form is submitted
if (isset($_GET['query'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['query']);
    $sql = "SELECT * FROM blogs WHERE blog_title LIKE '%$searchTerm%' ORDER BY created_at DESC";
} else {
    // If not submitted, fetch all blogs
    $sql = "SELECT * FROM blogs ORDER BY created_at DESC";
}

$sql_query = mysqli_query($conn, $sql);
$blogs = mysqli_fetch_all($sql_query, MYSQLI_ASSOC);

?>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Search Blog</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body border">
                <form method="GET" action="" class="border border-dark d-flex align-items-center justify-content-between rounded shadow-blue p-1">
                    <i style="font-size: 20px;" class="bi bi-search text-dark ms-2"></i>
                    <input type="text" class="outline-none border-none mx-2" name="query" id="search" placeholder="Search blogs..." style="border: none; outline: none; background: transparent; width: 100%">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="container" style="margin-top: 100px;">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3><?php echo isset($_GET['category']) ? ucwords($category) : 'Search Results' ?></h3>
        <i style="font-size: 25px;" class="bi bi-search text-dark" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
    </div>
    <?php if (!empty($blogs)) : ?>
        <?php foreach ($blogs as $blog) : ?>
            <div class="border shadow rounded mb-4">
                <div class="d-block d-lg-flex align-items-center">
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
                    <div style="flex: 1;">
                        <img src="<?php echo $img_src ?>" alt="<?php echo $blog['blog_title'] ?>" style="height: 350px; width: 100%; object-fit: cover">
                    </div>
                    <div class="p-4" style="flex: 2">
                        <h3><?php echo ucwords($blog['blog_title']) ?></h3>
                        <p><?php echo ucfirst(substr($blog['blog_content'], 0, 200)) . '...'; ?></p>
                        <a href=<?php echo "blog.php?id={$blog['blog_id']}" ?> class="btn btn-primary">READ MORE</a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    <?php else : ?>
        <p>No blogs found.</p>
    <?php endif ?>
</div>

<?php include('./partials/footer.php'); ?>