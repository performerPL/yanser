<?php
require_once '_header.php';
require_once '../config/_app.php';
require_once '../lib/ftp.php';
require_once '../lib/menu.php';
require_once '../lib/gallery.php';
_sec_authorise(ACCESS_MIN_EDITOR);

$ftp = new File_Manager_System('../');

$Tab = $_POST;
$ID = _get_post('group_id', 0);
if ($ID == '') {
  $ID = $_GET['group_id'];
}
$Error = array();
//$tt = group_get($ID);

$Message = '';
if ($_GET['dir'] == '') {
  $dir = '/';
} else {
  $dir = $_GET['dir'];
}
$_cmd_get = $_GET['cmd'];
$tt = group_get($ID, $dir);
//$CloseMsg='';
//if(!$tt)

//_redirect('index_ftp.php');
switch ($_POST['cmd']) {
  case 'add2':
    $ID = _get_post('group_id', 0);
    $tt = group_get($ID);
    if (!$tt) {
      exit;
    }
    if (isset($_POST['add_file'])) {
      $add_file = $_POST['add_file'];
      if (!is_array($add_file)) {
        $add_file = array($add_file);
      }
      foreach ($add_file as $file) {
        $Tab = array(
          'file_id' => $file,         
          'group_id' => $ID
        );
        group_file_add($Tab);
      }
    }
    //_redirect("edit_group.php?group_id=$ID");
    //    $CloseMsg = 'window.top.updatePicturesList();';
    //    $CloseMsg = 'window.top.location = window.top.location;';
    break;
    
  case 'add':
    if ($R = $ftp->uploadFile('file_name', $_POST, $_POST['dir_curr'])) {
      $Message = $T['mod_ftp_file_uploaded'];
    }
    break;
    
  case 'edit' :
    $Error = group_validate($Tab, $T);
    if (count($Error) == 0) {
      $x = group_update($Tab);
      if ($x > 0) {
        _redirect('edit_group.php?group_id=' . intval($ID));
      } else {
        $Message = $T['update_error_msg'];
      }
    }
    break;

  case 'add_dir':
    if ($ftp->createDirectory($_POST['dir_name'], $_POST['dir_curr']== '' ? '/' : $_POST['dir_curr'], $ID)) {
      $Message = $T['mod_ftp_dir_created'] . ' ' . $_POST['dir_name'];
    }
    break;

  case 'delete' :
    if (intval($ID) > 0 && group_delete($ID)) {
      _redirect('edit_group.php#i_' . $x . '?group_id=' . intval($ID));
    } else {
      $Message = $T['delete_error_msg'];
    }
    break;

  case 'upload':
    if ($ftp->uploadFile('file_name', $_POST, $_POST['dir_curr'])) {
      $Message = $T['mod_ftp_file_uploaded'];
    }
    break;

  case 'edit_file':
    if (editFile($_POST)) {
      $SHOWLINE = true;
      require_once 'tpl/edit_file.html.php';
      exit;
    }
    break;
}

switch ($_cmd_get) {
  case 'delete':
    if (!$ftp->file_del($_GET["file_id"], $_GET['file_type'], $_GET['file_name'], $_GET['file_dir'])) {
      $Message = $T['delete_file_error_msg'];
    }
    break;

  case 'delete_filegroup':
    if (!$ftp->file_group_del($_GET["file_id"], $_GET['group_id'])) {
      $Message = $T['delete_file_error_msg'];
    }
    break;

  case 'edit_file':
    $Message = '';
    //echo 'as';
    $file = get_file_info($_GET['file_id']);
    //print_r($file);
    $SHOWLINE = false;
    require_once 'tpl/edit_file.html.php';
    exit;
    require_once '_footer.php';
    break;

  case 'show_file':
      $file = get_file_info($_GET['file_id']);
    //print_r($file);
    $SHOWLINE = true;
    require_once 'tpl/edit_file.html.php';
    exit;
  
  default:
    break;
}
if ($CloseMsg != '') {
  require_once 'tpl/modal_close.html.php';
}
$Tab = _merge($tt, $Tab);
$Tab['menu_access'] = group_get_menu_access($ID);
$Tab['menu_list'] = menu_list();
//echo $dir;

require_once 'tpl/header.html.php';
require_once 'tpl/edit_group.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
