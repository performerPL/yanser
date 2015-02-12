<?php
require_once('_header.php');
require_once('../lib/config.php');
require_once('../lib/config_value.php');
_sec_authorise(ACCESS_MIN_SUPERADMIN);


$Message = '';

$TabConf= config_tree();
$Tab= config_value_tree('',true);


require_once('tpl/header.html.php');
require_once('tpl/index_config_value.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
