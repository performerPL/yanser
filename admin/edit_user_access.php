<?php
require_once '_header.php';
require_once '../lib/user.php';
require_once '../lib/menu.php';
require_once '../lib/item.php';
_sec_authorise(ACCESS_MIN_ADMIN);

$Message = '';
$Error = array();
$ID = _get_post('user_id', 0);
$Tab = $_POST;

switch ($_POST['cmd']) {
  case 'edit':
    $x = user_update($Tab, true);
    if ($x > 0) {
      _redirect('index_user.php#i_' . intval($x));
    } else {
      $Message = $T['update_error_msg'];
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
require_once 'tpl/edit_user_access.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
