<?php
require_once('_header.php');
require_once('../lib/repo.php');
_sec_authorise(ACCESS_MIN_EDITOR);

$type= _get_post('type',FILE_ANY);
$field= _get_post('field','');


$Dir = ($type==FILE_ANY)?REPOSITORY:REPO_IMAGES;
$List = array();

if($field!='') {
	$List = repo_list($type,$Dir);
}

require_once('tpl/header_modal.html.php');
require_once('tpl/select_repo.html.php');
require_once('tpl/footer_modal.html.php');
require_once('_footer.php');