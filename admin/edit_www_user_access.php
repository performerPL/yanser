<?php
require_once '_header.php';
require_once '../lib/www_user.php';
_sec_authorise(ACCESS_MIN_ADMIN);

$Message = '';
$Error = array();
$ID = _get_post('wu_id', 0);
$Tab = $_POST;

switch ($_POST['cmd']) {
  case 'edit':
    $x = www_user_update($Tab, true);
    if ($x > 0) {
      _redirect('index_www_user.php#i_' . intval($x));
    } else {
      $Message = $T['update_error_msg'];
    }
    break;

  default:
    break;
}
if ($ID > 0) {
  $Tab = _merge(www_user_get($ID), $Tab);
}
$Tab['menu_access'] = www_user_get_group_access($ID);
$Tab['menu_list'] = www_user_group_list(0);
require_once 'tpl/header.html.php';
require_once 'tpl/edit_www_user_access.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
