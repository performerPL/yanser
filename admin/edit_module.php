<?php
require_once '_header.php';
require_once '../lib/user.php';
require_once '../lib/article.php';
require_once '../lib/item.php';
require_once '../lib/gallery.php';
require_once '../lib/ftp.php';
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';
$Error = array();
$CloseMsg = ''; // jeśli będzie ustawiona, to modalne okienko się zamknie

$ArticleID = _get_post('article_id', 0);
$ID = _get_post('module_id', 0);
$Type = _get_post('module_type', 0);

$Tab = $_POST;

switch ($_POST['cmd']) {
  case 'edit':
    $Error = article_mod_validate($Tab, $T);
    //print_r($Tab);
    if (count($Error)==0) {
      //var_dump($Tab);
      $x = article_mod_update($Tab);
      if ($x > 0) {
        // zamknięcie okna, przeładowanie stronki na dole
        //_redirect('index_promotion.php#i_'.intval($x));
        $CloseMsg = 'window.top.setTimeout("updateModuleList(\"\",0);", 10);';
      } else {
        $Message = $T['update_error_msg'];
      }
    }
    break;

  case 'delete':
    if (intval($ID) > 0 && article_mod_delete($ID)) {
      exit; // nothing
    } else {
      echo $T['delete_error_msg'];
      exit;
    }
    break;

  default:
    break;
}

if ($ID > 0) {
  $Tab = _merge(article_mod_get($ID), $Tab);
}

$AccessLevel = array(0=>"Niezalogowany",2=>"Zalogowany"); // poziomy dostępu zdefiniowane w systemie

$type_nam = $GL_MOD_TYPE[$Tab['module_type']]->script;
//print_r($Tab);
$include_form = '';
if (file_exists('module/' . $type_nam . '.html.php')) {
  $include_form = 'module/' . $type_nam . '.html.php';
}

if ($CloseMsg != '') {
  require_once 'tpl/modal_close.html.php';
} else {
  require_once 'tpl/edit_module.html.php';
}
require_once '_footer.php';
