<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

require_once '_header_unlogged.php';

ini_set('register_globals', false);
ini_set('magic_quotes_gpc', false);
set_magic_quotes_runtime(false);

if (get_magic_quotes_gpc()) { 
    function stripslashes_deep($value) 
    { 
        $value = is_array($value) ? 
                    array_map('stripslashes_deep', $value) : 
                    stripslashes($value); 

        return $value; 
    } 

    $_POST = array_map('stripslashes_deep', $_POST); 
    $_GET = array_map('stripslashes_deep', $_GET); 
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE); 
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST); 
}

if (!_sec_logged()) {
  _redirect('login.php');
}
require_once '../lib/user.php';
user_update_time();
require_once '../lib/config.php';
require_once '../lib/config_value.php';
// jeśli ma być używany memcache
if(USE_MEMCACHE == 1) {
require_once '../kernel/Classes/tools/Memcached.php';
}

$GL_CONF = config_value_tree();
