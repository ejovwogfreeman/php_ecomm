<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'gbuser');
define('DB_PASSWORD', 'gb12345');
define('DB_NAME', 'php_ecomm');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// if ($conn) {
//     echo 'connected successfully';
// };
