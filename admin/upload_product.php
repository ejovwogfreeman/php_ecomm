<?php

include('admincheck.php');
include('../config/db.php');
include('../partials/header.php');

if (isset($_POST['submit'])) {

    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = htmlspecialchars($_POST['price']);

    // Image handling
    $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];

    if (in_array($imageFileType, $allowedExtensions)) {
        $image = $_FILES['image']['tmp_name'];
        $imageData = file_get_contents($image);
        $imageData =  mysqli_real_escape_string($conn, $imageData);

        $sql = "INSERT INTO products (product_name, product_description, product_price, product_image) VALUES ('$name', '$description', '$price', '$imageData')";

        if (mysqli_query($conn, $sql)) {
            header('Location: /php_ecommerce/index.php');
        } else {
            echo "Error uploading product: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid file type. Allowed types: " . implode(', ', $allowedExtensions);
    }
}

?>

<div class="container" style="margin-top: 100px;">

    <form action="upload_product.php" class='border rounded p-3 mt-5 m-auto form-style' method="post" enctype="multipart/form-data">

        <h4 class="mb-3">UPLOAD A PRODUCT</h4>

        <div class="form-group mb-3">
            <label class="mb-2" for="name">Product Name:</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>

        <div class="form-group mb-3">
            <label class="mb-2" for="description">Product Description:</label>
            <textarea class="form-control" name="description" id="description" rows="4" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label class="mb-2" for="price">Product Price:</label>
            <input type="number" class="form-control" name="price" id="price" required>
        </div>

        <div class="form-group mb-3">
            <label class="mb-2" for="image">Select Image (PNG, JPG, JPEG, WebP):</label> <br>
            <input type="file" class="form-control-file border rounded p-2" name="image" id="image" accept="image/png, image/jpeg, image/webp" required style="width: 100% ">
        </div>

        <button type="submit" class="btn btn-primary" name="submit">Upload Product</button>
    </form>

</div>

<?php include('../partials/footer.php'); ?>