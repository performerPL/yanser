<?php
require_once '_header.php';
require_once '../lib/user.php';
require_once '../lib/menu.php';
require_once '../lib/item.php';
_sec_authorise(ACCESS_MIN_ADMIN);

$Message = '';
$Tab = user_list();
$Stats =  array(
	$T['user_count'] => count($Tab)
);

require_once 'tpl/header.html.php';
require_once 'tpl/index_user.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
