<?php
require_once '_header.php';
require_once '../lib/gallery.php';
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';
$Error = array();
$CloseMsg = '';

// lista z typem targetu
$pictureTargetList = array(
	"self" => "self",
	"blank" => "blank"
);

$ID = _get_post('gallery_id', 0);

$Tab = $_POST;
switch ($_POST['cmd']) {
	case 'add':
		$Error = array(); // TODO: validation
		if (count($Error)==0) {
			$image = gallery_image_upload('picture_file');
			if ($image) {
				$Tab['picture_file'] = $image;
				$x = gallery_image_add($Tab);
				if ($x>0) {
					//_redirect('index_promotion.php#i_'.intval($x));
					//$CloseMsg = 'window.top.updateModuleList("",0);';
					//$CloseMsg = 'alert("' . $T['picture_uploaded'] . '"); window.top.updatePicturesList();';
					$CloseMsg = 'window.top.setTimeout("updatePicturesList()", 10);';
				} else {
					$Message = $T['update_error_msg'];
				}
			}
		}
		break;
		
	default:
		break;
}


require_once 'tpl/add_gallery_image.html.php';
require_once '_footer.php';
