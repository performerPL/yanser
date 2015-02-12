<?php
require_once '_header.php';
require_once '../lib/user.php';
require_once '../lib/menu.php';
require_once '../lib/article.php';
require_once '../lib/item.php';
_sec_authorise(ACCESS_MIN_EDITOR);

if (!empty($_GET['go_to_pos'])) {
  $id = (int) $_GET['go_to_id'];
  header('Location: edit_item.php?item_id=' . $id . '#content');
  exit;
}

$Message = '';
$Menus = menu_list_access();

$MenuID = _get_post('menu_id', 0);
$ShowArchive = _get_post('archive', 0);

if ($MenuID==0) {
  reset($Menus);
  if (isset($_COOKIE[_sec_user('user_id')]['menu']) && isset($Menus[intval($_COOKIE[_sec_user('user_id')]['menu'])])) {
    $MenuID = intval($_COOKIE[_sec_user('user_id')]['menu']);
  } else {
    $MenuID = key($Menus);
  }
}
//echo '1:'.$ShowArchive.'<br />';
if ($ShowArchive == 0 && !isset($_GET['menu_id']) && !isset($_POST['menu_id'])) {
  if (isset($_COOKIE[_sec_user('user_id')]['show_arch'])) {
    $ShowArchive = intval($_COOKIE[_sec_user('user_id')]['show_arch']);
  }
}
//echo '2:'.$ShowArchive.'<br />';
$time = time()+60*60*24*30*12; // rok

//setcookie(_sec_user('user_id').'[menu]',intval($MenuID),$time);
//setcookie(_sec_user('user_id').'[show_arch]',($ShowArchive>0?1:0),$time);

$Tab = item_tree($MenuID, 0, $ShowArchive);

$s = item_stats($MenuID);
$Stats = array(
$T['count_all_item'] => $s['all'],
$T['count_visible_item']  => $s['visible'],
$T['count_unpublished_item'] => $s['unpublished'],
);

$ShowLevel= 5;

require_once 'tpl/header.html.php';
require_once 'tpl/index_item.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';

function menu_txt_func($k, $v)
{
  return $v['menu_name'];
}