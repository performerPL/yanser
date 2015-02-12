<?php
require_once('_header.php');
require_once('../lib/ftp.php');
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';
$Error = array();
$CloseMsg='';
$ftp = new File_Manager_System('../');
$ID = _get_post('group_id', 0);
if ($ID=='') {
	$ID=$_GET['group_id'];
}
if ($_GET['dir']=='') {
	$dir = '/'; 
} else {
	$dir = $_GET['dir'];
}

$Tab = $_POST;
switch ($_POST['cmd']) {
	case 'add':	
		if ($ftp->uploadFile('file_name', $_POST, $_POST['dir_curr']))	{
			$Message=$T['mod_ftp_file_uploaded'];
			$CloseMsg = 'window.top.setTimeout("updateFilesList()", 10);';
		}
	break;
	default:
		break;
}


require_once 'tpl/add_group_file.html.php';
