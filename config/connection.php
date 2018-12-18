<?php

define('HOST', '127.0.0.1');
define('USER', 'root');
define("PASS", '');
define("DB", 'coreproject');

$connection = mysqli_connect(HOST, USER, PASS, DB);
if (!$connection) {
    die(mysqli_error($connection));

}