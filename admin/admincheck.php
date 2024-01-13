<?php

session_start();
if (isset($_SESSION['user']) && $_SESSION['user'][0]['is_admin'] === "false") {
    header('Location: /php_ecommerce/404.php');
} else if (!isset($_SESSION['user'])) {
    header('Location: /php_ecommerce/login.php');
}
