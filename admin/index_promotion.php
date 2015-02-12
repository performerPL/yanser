<?php
require_once('_header.php');
require_once('../lib/trans.php');
require_once('../lib/promotion.php');
_sec_authorise(ACCESS_MIN_EDITOR);


$Message = '';
$Tab= promotion_list();
$Stats =  array(
	$T['promotion_count'] => count($Tab)
);

require_once('tpl/header.html.php');
require_once('tpl/index_promotion.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
