<?php

session_start();
if (isset($_SESSION['user']) && $_SESSION['user'][0]['is_admin'] === "false") {
    header('Location: /php_ecomm/404.php');
}
