<?php

session_start();
$_SESSION['user_id'] === false;
header('Location: login.php');
session_destroy();
