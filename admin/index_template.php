<?php
require_once('_header.php');
require_once('../lib/template.php');
_sec_authorise(ACCESS_MIN_EDITOR);



$Message = '';
$Tab= template_list();
$Stats =  array(
	$T['template_count'] => count($Tab)
);

require_once('tpl/header.html.php');
require_once('tpl/index_template.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
