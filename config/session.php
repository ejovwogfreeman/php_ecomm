<?php

session_start();
if (isset($_SESSION['user']) === false) {
    header('Location: /php_ecommerce/login.php');
}
