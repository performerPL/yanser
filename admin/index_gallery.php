<?php
require_once('_header.php');
require_once('../lib/gallery.php');
_sec_authorise(ACCESS_MIN_EDITOR);


$Message = '';
$Tab = gallery_list_access();
$Stats = array(
	$T['gallery_count'] => count($Tab)
);

require_once('tpl/header.html.php');
require_once('tpl/index_gallery.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
