<?php
require_once '_header.php';
require_once '../lib/user.php';
require_once '../lib/menu.php';
require_once '../lib/item.php';

$Message = '';
$Error = array();
$ID = _get_post('user_id', 0);
$Tab = $_POST;

switch($_POST['cmd']) {
  case 'edit':
    $Error = user_validate($Tab, $T);
    if (count($Error)==0) {
      $x = user_update($Tab);
      if ($x > 0) {
        _redirect('index_user.php#i_' . intval($x));
      } else {
        $Message = $T['update_error_msg'];
      }
    }
    break;

  case 'delete':

    if (intval($ID) > 0 && user_delete($ID)) {
      if (_sec_authorised(ACCESS_MIN_ADMIN)) {	
      _redirect('index_user.php#content');
      } else {
        _redirect('index.php');
      }
    } else {

      $Message = $T['delete_error_msg'];
    }
    break;

  default:
    break;
}
if ($ID > 0) {
  $Tab = _merge(user_get($ID), $Tab);
}
$Tab['menu_access'] = user_get_menu_access($ID);
$Tab['menu_list'] = menu_list();
require_once 'tpl/header.html.php';
require_once 'tpl/edit_user.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
