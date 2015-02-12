<?php
require_once('_header.php');
require_once('../lib/ftp.php');
_sec_authorise(ACCESS_MIN_EDITOR);

$Tab = $_POST;

switch($_POST['cmd']) 
{
  case 'add' :
    $Error = group_validate($Tab, $T);
    if(count($Error) == 0) 
	{
      $x = group_update($Tab);
      if($x > 0) {
        _redirect('index_ftp.php#i_' . $x);
      } else {
        $Message = $T['update_error_msg'];
      }
    }
    break;
}

$Message = '';

require_once('tpl/header.html.php');
require_once('tpl/add_files_group.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
