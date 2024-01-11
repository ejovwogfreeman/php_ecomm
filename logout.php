<?php

session_start();
$_SESSION['user_id'] === false;
$message = 'You have logged out successfully, Login to continue shopping!';
header('Location: login.php?message=' . urldecode($message));
session_destroy();
