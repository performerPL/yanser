<?php
require_once '_header.php';
require_once '../lib/ftp.php';
_sec_authorise(ACCESS_MIN_EDITOR);

$Error = array();
//$images = gallery_readdir();

$Message = '';
switch ($_REQUEST['cmd']) {
  
  case 'add':
    $ID = _get_post('group_id', 0);
    $tt = group_get($ID);
    if (!$tt) {
      exit;
    }
    if (isset($_POST["add_file"])) {
      $add_file = $_POST["add_file"];
      if (!is_array($add_file)) {
        $add_file = array($add_file);
      }
      foreach($add_file as $file) {
        $Tab = array(
					"file_id" => $file,					
					"group_id" => $ID);
        group_file_add($Tab);
      }
    }
    _redirect("edit_group.php?group_id=$ID");
    //		$CloseMsg = 'window.top.updatePicturesList();';
    //		$CloseMsg = 'window.top.location = window.top.location;';
    break;
    
  case 'list':
    $list = $_REQUEST['list'];
    if ($list == "/") {
      $images = group_files_all(0);
    } elseif(intval($list) > 0) {
      $images = group_files(intval($list));
    }
    require_once 'tpl/add_group_files_ajax.html.php';
    exit;
    break;
    
  default:
    $ID = _get_post('group_id', 0);
    $tt = group_get($ID);
    if (!$tt) {
    exit;
    }

    break;
}

require_once 'tpl/add_group_files.html.php';