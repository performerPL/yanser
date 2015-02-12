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
    $Error = null; //www_user_validate($Tab, $T);
    if (count($Error)==0) {
      $x = www_user_update($Tab);
      if ($x > 0) {
        _redirect('index_www_user.php#i_' . intval($x));
      } else {
        $Message = $T['update_error_msg'];
      }
    }
    break;

  case 'delete':

    if (intval($ID) > 0 && www_user_delete($ID)) {
      if (_sec_authorised(ACCESS_MIN_ADMIN)) {  
      _redirect('index_www_user.php#content');
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
  $Tab = _merge(www_user_get($ID), $Tab);
}
// lista z grupami do ktorych nalezy user
$Tab['menu_access'] = www_user_get_group_access($ID);
// lista z grupami
$Tab['menu_list'] = www_user_group_list(0);

require_once 'tpl/header.html.php';
require_once 'tpl/edit_www_user.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
