<style>
    .input {
        width: 50%;

        @media (max-width: 340px) {
            width: 45%;
        }
    }

    .input:focus {
        outline: none;
    }
</style>

<?Php
$isIndexPage = basename($_SERVER['PHP_SELF']) == 'index.php';
$isAdminPage = strpos($_SERVER['PHP_SELF'], 'admin') !== false;

$marginTopClass = $isIndexPage && !$isAdminPage ? 'mt-0' : 'mt-4';
?>

<footer class='text-center <?php echo $marginTopClass; ?> py-3 bg-dark text-light'>
    <div class="container pt-5 pb-3 inner-footer">
        <ul class="text-start">
            <h5 class="mb-2">Categories</h5>
            <li><a class="text-light" href="">Electronics</a></li>
            <li><a class="text-light" href="">Laptops</a></li>
            <li><a class="text-light" href="">Accessories</a></li>
            <li><a class="text-light" href="">Phones</a></li>
        </ul>
        <ul class="text-start">
            <h5 class="mb-2">Customer Service</h5>
            <li><a class="text-light" href="">Contact Us</a></li>
            <li><a class="text-light" href="">FAQ</a></li>
            <li><a class="text-light" href="">Give Us Feedback</a></li>
        </ul>
        <ul class="text-start">
            <h5 class="mb-2">About Us</h5>
            <li><a class="text-light" href="">About</a></li>
            <li><a class="text-light" href="">Location</a></li>
            <li><a class="text-light" href="">Contact Us</a></li>
        </ul>
        <ul class="text-start">
            <h5 class="mb-2">Follow Us</h5>
            <li><a class="text-light" href=""><i class="bi bi-facebook"></i><span class="ms-2">Facebook</span></a></li>
            <li><a class="text-light" href=""><i class="bi bi-instagram"></i><span class="ms-2">Instagram</span></a></li>
            <li><a class="text-light" href=""><i class="bi bi-twitter"></i><span class="ms-2">Twitter</span></a></li>
        </ul>
        <ul class="text-start">
            <h5 class="mb-2">Subscribe to our Newsletter</h5>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="text" class="bg-transparent px-2 input" placeholder="example@gmail.com" aria-label="email" aria-describedby="basic-addon1" style="border: 1px solid white; color: white;">
                <button class="btn" type="button" style="border: 1px solid white; color: white">SUBSCRIBE</button>
            </div>
        </ul>
    </div>
    <p class="m-0">copyright &copy; <?php echo date('Y') ?> Tech360</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>