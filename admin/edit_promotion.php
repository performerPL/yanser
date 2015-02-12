<?php
require_once '_header.php';
require_once '../lib/trans.php';
require_once '../lib/promotion.php';
require_once '../lib/menu.php';
require_once '../lib/www_user.php';
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';
$Error = array();
$ID = _get_post('promotion_id', 0);
$Tab = $_POST;

switch ($_POST['cmd']) {
  case 'edit':
    $Error = promotion_validate($Tab, $T);
    if (count($Error) == 0) {
      //var_dump($Tab);
      $x = promotion_update($Tab);
      if ($x > 0) {
        _redirect('index_promotion.php#i_' . intval($x));
      } else {
        $Message = $T['update_error_msg'];
      }
    }
    break;

  case 'delete':
    if (intval($ID) > 0 && promotion_delete($ID)) {
      _redirect('index_promotion.php#content');
    } else {
      $Message = $T['delete_error_msg'];
    }
    break;

  default:
    break;
}
if ($ID > 0) {
  $Tab = _merge(promotion_get($ID), $Tab);
}

$Tab['menu_list'] = menu_list();

// lista z grupami uzytkowników(=newsletter)
$Tab['newsletter_group_list'] = www_user_group_list(0);

//var_dump($Tab);

require_once 'tpl/header.html.php';
require_once 'tpl/edit_promotion.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
