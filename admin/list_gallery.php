<?php
require_once('_header.php');
require_once('../lib/gallery.php');
_sec_authorise(ACCESS_MIN_EDITOR);

header("Content-type: text/html; charset=utf-8");

$Tab = $_POST;
$ID = _get_post('gallery_id', 0);
$Error = array();
$tt = gallery_get($ID);
if(!$tt)
  exit;
$Message = '';

switch($_POST['cmd']) {
	case 'delete':
		if(isset($_POST["ord"]) && $_POST["ord"])
			gallery_image_del($ID, $_POST["ord"]);
		break;
	default:
		break;
}

$images = gallery_images_list($ID);

require_once('tpl/list_gallery.html.php');
require_once('_footer.php');

