<?php

require("env.php");

if ($mode !== 'production') {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'baseballitem');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    return;
}

define('DB_HOST', $db_host);
define('DB_NAME', $db_name);
define('DB_USER', $db_user);
define('DB_PASS', $db_pass);
