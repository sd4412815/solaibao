<?php
// database host
$db_host   = "localhost:3306";

// database name
$db_name   = "ecshop";

// database username
$db_user   = "root";

// database password
$db_pass   = "root";

// table prefix
$prefix    = "slb_";

$timezone    = "PRC";

$cookie_path    = "/";

$cookie_domain    = "";

$session = "1440";

define('EC_CHARSET','utf-8');

define('DEBUG_MODE',2);

//define('ADMIN_PATH','admin');
if(!defined('ADMIN_PATH'))
{
    define('ADMIN_PATH','slb_manager');
}
define('AUTH_KEY', 'this is a key');

define('OLD_AUTH_KEY', '');

define('API_TIME', '2016-05-17 13:20:55');

?>