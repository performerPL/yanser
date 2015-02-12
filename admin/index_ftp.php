<?php
require_once('_header.php');
require_once('../lib/ftp.php');
_sec_authorise(ACCESS_MIN_EDITOR);

error_reporting(ALL);

$Message = '';
$ftp = new File_Manager_System('../');
$Tab = files_list_access();
$Stats = array(
	$T['groups_count'] => count($Tab)
);

//
if($_GET['dir']=='')
	$dir = $ftp->mainDirectory; 
else
{
	$dir = $ftp->mainDirectory.$_GET['dir'];
}
if($_FILES['file_name'])
{
	if($ftp->uploadFile('file_name', $_POST['dir_curr']))
		$Message=$T['mod_ftp_file_uploaded'];
}
	
require_once('tpl/header.html.php');
require_once('tpl/index_ftp.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
