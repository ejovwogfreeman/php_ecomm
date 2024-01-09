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

$sql = "SELECT * FROM products WHERE product_id = '$id'";

$sql_query = mysqli_query($conn, $sql);

$product = mysqli_fetch_assoc($sql_query);

if (isset($product['product_name'])) {
    $name = $product['product_name'];
}

if (isset($product['product_description'])) {
    $description = $product['product_description'];
}

if (isset($product['product_price'])) {
    $price = $product['product_price'];
}

$Err = '';

if (isset($_POST['submit'])) {
    if (empty($_POST['name'])) {
        $Err = 'PLEASE ADD PRODUCT NAME';
    } else {
        $name = htmlspecialchars($_POST['name']);
        $category = htmlspecialchars($_POST['category']);
        if (empty($_POST['description'])) {
            $Err = 'PLEASE ADD PRODUCT DESCRIPTION';
        } else {
            $description = htmlspecialchars($_POST['description']);
            if (empty($_POST['price'])) {
                $Err = 'PLEASE ADD PRODUCT PRICE';
            } else {
                $price = htmlspecialchars($_POST['price']);
                if (empty($_FILES['image']['name'])) {
                    $Err = 'PLEASE ADD PRODUCT IMAGE';
                } else {
                    // Image handling
                    $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];

                    if (in_array($imageFileType, $allowedExtensions)) {
                        $image = $_FILES['image']['tmp_name'];
                        $imageData = file_get_contents($image);
                        $imageData =  mysqli_real_escape_string($conn, $imageData);

                        $productId = $_POST['id'];

                        $sql = "UPDATE products SET product_name = '$name', product_category = '$category', product_description='$description', product_price='$price', product_image='$imageData' WHERE product_id = '$productId'";

                        if (mysqli_query($conn, $sql)) {
                            $message = 'Product Updated Successfully';
                            redirectWithMessage($message);
                            exit();
                        } else {
                            echo "Error updating product: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Invalid file type. Allowed types: " . implode(', ', $allowedExtensions);
                    }
                }
            }
        }
    }
}

ob_end_flush();

?>

<div class="container" style="margin-top: 100px;">

    <form action="update_product.php" class='border rounded p-3 pt-5 mt-5 m-auto form-style' method="post" enctype="multipart/form-data">
        <?php if ($Err) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $Err ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php endif ?>
        <h4 class="mb-3">UPDATE PRODUCT</h4>
        <input type="text" class="form-control" name="id" id="id" value="<?php echo $id ?>" hidden>
        <div class="form-group mb-3">
            <label class="mb-2" for="name">Product Name:</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo $name ?>">
        </div>

        <div class="form-group mb-3">
            <label class="mb-2" for="name">Product Category:</label>
            <select class="form-select" aria-label="Default select example" name="category">
                <option value="electronics">Electronics</option>
                <option value="laptops">Laptops</option>
                <option value="accessories">Accessories</option>
                <option value="Phones">Phones</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label class="mb-2" for="description">Product Description:</label>
            <textarea class="form-control" name="description" id="description" rows="4"><?php echo $description ?></textarea>
        </div>

        <div class="form-group mb-3">
            <label class="mb-2" for="price">Product Price:</label>
            <input type="number" class="form-control" name="price" id="price" value="<?php echo $price ?>">
        </div>

        <div class="form-group mb-3">
            <label class="mb-2" for="image">Select Image (PNG, JPG, JPEG, WebP):</label> <br>
            <input type="file" class="form-control-file border rounded p-2" name="image" id="image" accept="image/png, image/jpeg, image/webp" style="width: 100% ">
        </div>

        <button type="submit" class="btn btn-primary" name="submit">Update Product</button>
    </form>

</div>

<?php include('../partials/footer.php'); ?>