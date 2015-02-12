<?php
require_once '_header.php';
require_once '../lib/ftp.php';
_sec_authorise(ACCESS_MIN_EDITOR);

header("Content-type: text/html; charset=utf-8");

$Tab = $_POST;
$ID = _get_post('group_id', 0);
if ($ID == '') {
  $ID = $_GET['group_id'];
}
$Error = array();
$tt = group_get($ID);
if (!$tt) {
  exit;
}
$Message = '';
//print_r($_GET);

//$images = gallery_images_list($ID);
$ftp = new File_Manager_System('../');
if ($_GET['dir']=='') {
  $dir = $ftp->mainDirectory;
} else {
  $dir = $_GET['dir'];
}
//echo $dir;
$images = group_files_list($ID, $dir);

require_once 'tpl/list_group.html.php';
require_once '_footer.php';

