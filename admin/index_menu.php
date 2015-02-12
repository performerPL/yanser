<?php
require_once('_header.php');
require_once('../lib/user.php');
require_once('../lib/menu.php');
require_once('../lib/item.php');
_sec_authorise(ACCESS_MIN_EDITOR);


$Message = '';
$Tab = menu_list_access();
$Stats =  array(
	$T['menu_count'] => count($Tab)
);

require_once('tpl/header.html.php');
require_once('tpl/index_menu.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
