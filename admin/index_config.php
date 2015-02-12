<?php
require_once('_header.php');
require_once('../lib/config.php');
_sec_authorise(ACCESS_MIN_SUPERADMIN);


$Message = '';
$Tab= config_tree();
$Stats =  array(
	$T['config_count'] => count($Tab)
);

require_once('tpl/header.html.php');
require_once('tpl/index_config.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
