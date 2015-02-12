<?php
require_once '_header.php';
require_once '../lib/user.php';
require_once '../lib/menu.php';
require_once '../lib/item.php';
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';
$Error = array();
$ID = _get_post('menu_id', 0);
$Tab= $_POST;

switch ($_POST['cmd']) {
  case 'edit':
    $Error = menu_validate($Tab, $T);
    if (count($Error) == 0) {
      $x = menu_update($Tab);
      if ($x > 0) {
        _redirect('index_menu.php#i_' . intval($x));
      } else {
        $Message = $T['update_error_msg'];
      }
    }
    break;

  case 'delete':

    if (intval($ID) > 0 && menu_delete($ID)) {
      _redirect('index_menu.php#content');
    } else {
      $Message = $T['delete_error_msg'];
    }
    break;

  default:
    break;
}
if ($ID > 0) {
  $Tab = _merge(menu_get($ID), $Tab);
}
// get addons for menu

require_once 'tpl/header.html.php';
require_once 'tpl/edit_menu.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
