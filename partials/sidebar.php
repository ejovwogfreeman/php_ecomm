<ul class="sidebar rounded border">
    <li><a href="/php_ecommerce/dashboard.php"><i class="bi bi-speedometer"></i><span class="a">Dashboard</span></a></li>
    <li><a href="/php_ecommerce/orders.php"><i class="bi bi-cart-check"></i><span class="a">Orders</span></a></li>
    <?php if (isset($_SESSION['user']) && $_SESSION['user'][0]['is_admin'] === 'true') : ?>
        <li><a href="/php_ecommerce/admin"><i class="bi bi-person-plus"></i>
                <spapn class="a">Admin</spapn>
            </a></li>
        <li><a href="/php_ecommerce/admin/users.php"><i class="bi bi-people"></i>
                <spapn class="a">Users</spapn>
            </a></li>
        <li><a href="/php_ecommerce/admin/products.php"><i class="bi bi-box-seam"></i>
                <spapn class="a">Products</spapn>
            </a></li>
        <li><a href="/php_ecommerce/admin/blogs.php"><i class="bi bi-journal"></i>
                <spapn class="a">Blogs</spapn>
            </a></li>
        <li><a href="/php_ecommerce/admin/referral_earnings.php"><i class="bi bi-credit-card"></i>
                <spapn class="a">Earnings</spapn>
            </a></li>
        <li><a href="/php_ecommerce/admin/upload_product.php"><i class="bi bi-cloud-arrow-up"></i><span class="a">Upload Product</span></a></li>
        <li><a href="/php_ecommerce/admin/create_blog.php"><i class="bi bi-pencil-square"></i></i><span class="a">Create Blog</span></a></li>
    <?php endif ?>
    <li><a href="/php_ecommerce/profile.php"><i class="bi bi-person-circle"></i><span class="a">Profile</span></a></li>
    <li><a href="/php_ecommerce/settings.php"><i class="bi bi-gear"></i><span class="a">Settings</span></a></li>
    <li><a href="/php_ecommerce/change_password.php"><i class="bi bi-shield-lock"></i><span class="a">Change Password</span></a></li>
</ul>