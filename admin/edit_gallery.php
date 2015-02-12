<?php
require_once '_header.php';
require_once '../lib/gallery.php';
require_once '../lib/menu.php';
_sec_authorise(ACCESS_MIN_EDITOR);

// znacznik czy zapytanie ajax
$isAjax = _get_post('ajax', 0);
// dla zapytań ajax sprawdzenie galerii nie będzie wykonane
if($isAjax != 1) {
	$Tab = $_POST;
	$ID = _get_post('gallery_id', 0);
	$Error = array();
	$tt = gallery_get($ID);
	if (!$tt) {
		_redirect('index_gallery.php');
	}
}

switch ($_POST['cmd']) {
	case 'add':
		$Error = array(); // TODO: validation
		if (count($Error)==0) {
			$original = (bool) $Tab['original'];
			$image = gallery_image_upload('picture_file', $original);
			if ($image) {
				$Tab['picture_file'] = $image;
				$x = gallery_image_add($Tab);
				if ($x>0) {
					_redirect('edit_gallery.php?gallery_id=' . intval($ID));
				} else {
					$Message = $T['update_error_msg'];
				}
			}
		}

		break;

	case 'edit' :
		$Error = gallery_validate($Tab, $T);
		if (count($Error) == 0) {
			$x = gallery_update($Tab);
			if ($x > 0) {
				_redirect('edit_gallery.php?gallery_id=' . intval($ID));
			} else {
				$Message = $T['update_error_msg'];
			}
		}

		break;

	case 'delete' :
		if (intval($ID) > 0 && gallery_delete($ID)) {
			_redirect('edit_gallery.php#i_' . $x . '?gallery_id=' . intval($ID));
		} else {
			$Message = $T['delete_error_msg'];
		}

		break;
		 
	case 'image_exists' : // akcja sprawdzenia czy obrazek juz istnieje w galerii

		if(gallery_image_exists(_get_post('filename', '')) == true) {
			echo 1;
		}
		else {
			echo 0;
		}
		exit;

		break;
}
$Tab = _merge($tt, $Tab);
$Tab['menu_access'] = gallery_get_menu_access($ID);
$Tab['menu_list'] = menu_list();
$Message = '';

require_once 'tpl/header.html.php';
require_once 'tpl/edit_gallery.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
