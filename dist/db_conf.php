<?php

$mode = '';

if ($mode !== 'production') {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'baseballitem');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    return;
}

define('DB_HOST', 'production_info');
define('DB_NAME', 'production_info');
define('DB_USER', 'production_info');
define('DB_PASS', 'production_info');
