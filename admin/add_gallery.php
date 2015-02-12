<?php
require_once('_header.php');
require_once('../lib/gallery.php');
_sec_authorise(ACCESS_MIN_EDITOR);

$Tab = $_POST;

switch($_POST['cmd']) {
  case 'add' :
    $Error = gallery_validate($Tab, $T);
    if(count($Error) == 0) {
      $x = gallery_update($Tab);
      if($x > 0) {
        _redirect('index_gallery.php#i_' . $x);
      } else {
        $Message = $T['update_error_msg'];
      }
    }
    break;
}

$Message = '';

require_once('tpl/header.html.php');
require_once('tpl/add_gallery.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
