<?php
header("Content-type: text/html; charset=utf-8");
require_once '_header.php';
require_once '../lib/gallery.php';
_sec_authorise(ACCESS_MIN_EDITOR);
if (get_magic_quotes_gpc()) {
  $_GET = array_map('stripslashes', $_GET);
  $_POST = array_map('stripslashes', $_POST);
}

$Message = '';
$Error = array();

// lista z typem targetu
$pictureTargetList = array(
	"self" => "self",
	"blank" => "blank"
);


$ID = _get_post('picture_id', 0);
$type = _get_post('type');
$edit = _get_post('edit');
$image = gallery_picture_get($ID);
if (!$image) {
  exit;
}

if ($_POST['cmd'] == 'edit') {
  $Tab = $image;
  $Tab["picture_description"] = $_POST["picture_description"];
  $Tab["picture_title"] = $_POST["picture_title"];
  $Tab["picture_target_url"] = $_POST["picture_target_url"];
  $Tab["picture_target"] = $_POST["picture_target"];
  gallery_image_add($Tab);
  $image = gallery_picture_get($ID);
}

require_once 'tpl/edit_gallery_picture_data.html.php';
require_once '_footer.php';
