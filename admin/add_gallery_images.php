<?php
require_once '_header.php';
require_once '../lib/gallery.php';
_sec_authorise(ACCESS_MIN_EDITOR);

$Error = array();
//$images = gallery_readdir();

$Message = '';
switch ($_REQUEST['cmd']) {
	case 'add':
		$ID = _get_post('gallery_id', 0);
		$tt = gallery_get($ID);
		if (!$tt) {
			exit;
		}

		if (isset($_POST['add_image'])) {
			$add_image = $_POST['add_image'];
			if (!is_array($add_image)) {
				$add_image = array($add_image);
			}
			foreach ($add_image as $image) {
				$data = gallery_picture_data($image, $_REQUEST['list']);
				$Tab = array(
					'picture_title' => $data['picture_title'],
					'picture_description' => $data['picture_description'],
					'gallery_id' => $ID ,
					'picture_file' => $image);
				gallery_image_add($Tab);
			}
		}
		_redirect("edit_gallery.php?gallery_id=$ID");
		//		$CloseMsg = 'window.top.updatePicturesList();';
		//		$CloseMsg = 'window.top.location = window.top.location;';
	case 'list':
		$list = $_REQUEST['list'];
		if ($list == '/') {
			// opcja wyłaczona gdyż dane są niespójne, częśc plikow wrzucana jest przez FTP
			//$images = gallery_images_files_all(0);
			$images = gallery_readdir();
		} 
		// gdy wyswiatlanie plików nie przypisanych do żadnej galerii
		else if(empty($list)) {
			// pobiera pliki przypisane do galerii
			$galleryImages = gallery_images_files_all();
			// pobiera wszystkie pliki na ftp
			$imagesAll = gallery_readdir();
			// tablica z plikami nie przypisanymi
			$images = array();
			foreach($imagesAll as $image) {
				// gdy nie przypisany
				if(!in_array($image,$galleryImages))
					$images[] = $image;
			}
		}
		elseif (intval($list) > 0) {
			$images = gallery_images_files(intval($list));
		}
		require_once 'tpl/add_gallery_images_ajax.html.php';
		exit;
		break;

	default:
		$ID = _get_post('gallery_id', 0);
		$tt = gallery_get($ID);
		if (!$tt) {
			exit;
		}
		break;
}

require_once 'tpl/add_gallery_images.html.php';

require_once '_footer.php';
