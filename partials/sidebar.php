<ul class="sidebar rounded border">
    <li><a href="/php_ecommerce/dashboard.php"><i class="bi bi-speedometer"></i><span class="a">Dashboard</span></a></li>
    <?php if (isset($_SESSION['user']) && $_SESSION['user'][0]['is_admin'] === 'true') : ?>
        <li><a href="/php_ecommerce/admin"><i class="bi bi-person-plus"></i>
                <spapn class="a">Admin</spapn>
            </a></li>
        <li><a href="/php_ecommerce/admin/upload_product.php"><i class="bi bi-cloud-arrow-up"></i><span class="a">Upload Product</span></a></li>
    <?php endif ?>
    <li><a href="/php_ecommerce/profile.php"><i class="bi bi-person-circle"></i><span class="a">Profile</span></a></li>
    <li><a href="/php_ecommerce/settings.php"><i class="bi bi-gear"></i><span class="a">Settings</span></a></li>
    <li><a href="/php_ecommerce/change_password.php"><i class="bi bi-shield-lock"></i><span class="a">Change Password</span></a></li>
</ul>