<?php
require_once('_header.php');
require_once('../lib/repo.php');
_sec_authorise(ACCESS_MIN_EDITOR);


$type= _get_post('type',FILE_ANY);
$id= _get_post('id','');
$Dir = ($type==FILE_ANY)?REPOSITORY:REPO_IMAGES;

$id=str_replace('file_tree/',$Dir,$id);

$List = repo_list($type,$id);


require_once('tpl/select_repo_ajaxtree.php');
require_once('_footer.php');
