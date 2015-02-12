<?php
require_once '_header.php';
require_once '../lib/www_user.php';

$Message = '';
$Error = array();
$ID = _get_post('wug_id', 0);
$Tab = $_POST;

switch ($_POST['cmd']) {
  case 'edit':
      $x = www_user_group_update($Tab);
      if ($x > 0) {
        _redirect('index_www_groups.php#i_' . intval($x));
      } else {
        $Message = $T['update_error_msg'];
      }
    break;

  case 'delete':

    if (intval($ID) > 0 && www_group_delete($ID)) {
      if (_sec_authorised(ACCESS_MIN_ADMIN)) {  
      _redirect('index_www_groups.php#content');
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
  $Tab = _merge(www_user_group_get($ID), $Tab);
}

$Tab['group_list'] = www_user_group_list();
require_once 'tpl/header.html.php';
require_once 'tpl/edit_www_group.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
