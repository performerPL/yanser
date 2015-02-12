<?php
if (!defined('_APP')) {
	exit;
}
if (defined('_LIB_GALLERY.PHP')) {
	return;
}
define('_LIB_GALLERY.PHP', 1);
require_once 'user.php';

function gallery_list()
{
	global $GL_CONF;
	return _db_get(
		'SELECT *, (SELECT COUNT(*) FROM ' . DB_PREFIX . 'gallery_pic WHERE gallery_id = g.gallery_id) AS "count" ' .
		'FROM `'.DB_PREFIX.'gallery` g ' .
		'ORDER BY gallery_name','gallery_id');
}

function gallery_update($tab)
{
	global $GL_ACCESS_LVL;
	$t = array(
		'gallery_name'=>_db_string($tab['gallery_name']),
		'gallery_description'=>_db_string($tab['gallery_description']),
		'show_voting'=>_db_bool($tab['show_voting']),
	);
	if ($tab['gallery_id'] > 0) {
		// dostep menu
		$menu_check = array();
		$user_menu_access = gallery_get_menu_access($tab['gallery_id']);
		foreach ($user_menu_access as $key => $access) {
			if (!array_key_exists($access['menu_id'], $tab['allow_menu_access'])) {
				_db_query('DELETE from `'.DB_PREFIX.'mod_gallery_menu_access` WHERE menu_id='.intval($access['menu_id']).' and gallery_id='.intval($tab['gallery_id']));
			}
			$menu_check[$access['menu_id']] = 1;
		}

		if (is_array($tab['allow_menu_access'])) {
			foreach ($tab['allow_menu_access'] as $menu_id => $menu_access) {
				if (!array_key_exists($menu_id,$menu_check)) {
					$t_access = array(
					'gallery_id'=>$tab['gallery_id'],
					'menu_id'=>$menu_id,
					);
					_db_insert('mod_gallery_menu_access', $t_access);
				}
			}
		}
		// dostep do menu end
		return _db_update('gallery', $t, 'gallery_id=' . intval($tab['gallery_id']));
	} else {
		$new_gallery_id = _db_insert('gallery', $t);
		$MENUS = user_get_menu_access($_SESSION['cms_logged_user']['user_id']);
		foreach ($MENUS as $V) {
			$t_access = array(
					'gallery_id' => $new_gallery_id,
					'menu_id' => $V['menu_id'],
			);
			_db_insert('mod_gallery_menu_access', $t_access);
		}

		//		// dostep do menu
		//		if($new_gallery_id!=0){
		//		$user_menu_access = gallery_get_menu_access($new_gallery_id);
		//			foreach($user_menu_access as $key => $access){
		//				if(!array_key_exists($access['menu_id'],$tab['allow_menu_access'])){
			//					_db_query('DELETE from `'.DB_PREFIX.'mod_gallery_menu_access` WHERE menu_id='.intval($access['menu_id']).' and gallery_id='.intval($new_gallery_id));
			//				}
			//			}
			//			if(is_array($tab['allow_menu_access'])){
			//				foreach($tab['allow_menu_access'] as $menu_id => $menu_access){
			//					$t_access= array(
				//						'gallery_id'=>$new_gallery_id,
				//						'menu_id'=>$menu_id,
				//					);
				//					_db_insert('mod_gallery_menu_access',$t_access);
				//				}
				//		}
				//		}
				// dostep do menu end
				return $new_gallery_id;
}

}

function gallery_validate($tab,$T) {
	global $GL_CONF;
	$res = array();
	if(trim($tab['gallery_name']) == '')
	$res['gallery_name'] = $T['gallery_name_error'];
	return $res;
}

function gallery_get_all() {
	//	return _db_get('SELECT * FROM `'.DB_PREFIX.'gallery`');
	return gallery_list_access();
}
function gallery_list_access() {
	return _db_get('SELECT `'.DB_PREFIX.'gallery`.*  FROM `'.DB_PREFIX.'gallery`,`'.DB_PREFIX.'user_menu_access`,`'.DB_PREFIX.'mod_gallery_menu_access` where `'.DB_PREFIX.'user_menu_access`.user_id='.intval($_SESSION['cms_logged_user']['user_id']).' and `'.DB_PREFIX.'user_menu_access`.menu_id = `'.DB_PREFIX.'mod_gallery_menu_access`.menu_id and `'.DB_PREFIX.'mod_gallery_menu_access`.gallery_id = `'.DB_PREFIX.'gallery`.gallery_id ORDER BY gallery_name','gallery_id'); //dodać zarz±dzanie orderami
}

function gallery_get($id)
{
	return _db_get_one('SELECT * FROM `'.DB_PREFIX.'gallery` WHERE gallery_id='.intval($id));
}

function gallery_delete($id) {
	return _db_delete('gallery','gallery_id='.intval($id),1);
}

function gallery_image_scale($filename, $orginal, $output, $nwidth, $nheight)
{
	$width = imagesx($orginal);
	$height = imagesy($orginal);
	if ($width <= $nwidth && $height <= $nheight) {
		$x = $width;
		$y = $height;
	} else {
		if ($width / $nwidth > $height / $nheight) {
			$x = $nwidth;
			$y = $nwidth * $height / $width;
		} else {
			$y = $nheight;
			$x = $nheight * $width / $height;
		}
	}
	$scaled = imagecreatetruecolor($x, $y);
	imagecopyresampled($scaled, $orginal, 0, 0, 0, 0, $x, $y, $width, $height);
	if ($extension == '.png') {
		imagepng($scaled, $output);
	} elseif ($extension == '.gif') {
		imagegif($scaled, $output);
	} else {
		imagejpeg($scaled, $output);
	}
	return true;
}

function gallery_image_upload($name, $orig = false)
{
	global $GL_CONF;
	$filename = basename($_FILES[$name]['name']);
	if (!$filename) {
		return;
	}
	$extension = strtolower(strrchr($filename, "."));
	$orginal = false;
	switch ($extension) {
		case '.png':
			$orginal = imagecreatefrompng($_FILES[$name]['tmp_name']);
			break;

		case '.gif':
			$orginal = imagecreatefromgif($_FILES[$name]['tmp_name']);
			break;

		case '.jpeg':
		case '.jpg':
			$orginal = imagecreatefromjpeg($_FILES[$name]['tmp_name']);
			break;
	}
	if (!$orginal) {
		return false;
	}
	$cfg = $GL_CONF['IMAGES_FILES'];
	foreach ($cfg as $key => $value) {
		if (substr($key, 0, strlen('IMAGE_DIR_')) == 'IMAGE_DIR_') {
			$nr = substr($key, strlen('IMAGE_DIR_'));
			gallery_image_scale($filename, $orginal, '../' . $cfg['IMAGE_BASE_DIR'] . $value . $filename,
			$cfg["IMAGE_WIDTH_$nr"], $cfg["IMAGE_HEIGHT_$nr"]);
		}
	}
	if ($orig) {
		imagejpeg($orginal, '../' . $cfg['IMAGE_BASE_DIR'] . 'gallery/orig/' . $filename);
	}
	return $filename;
}

function gallery_image_add($tab)
{
	$t = array(
		'picture_title'=>_db_string($tab['picture_title']),
		'picture_description'=>_db_string($tab['picture_description']),
		'gallery_id'=>_db_int($tab['gallery_id']),
		'picture_file'=>_db_string($tab['picture_file']),
		'picture_order'=>_db_int(_db_new_order('gallery_pic','picture_order','gallery_id',$tab['gallery_id'])),
		'picture_target_url'=>_db_string($tab['picture_target_url']),
		'picture_target'=>_db_string($tab['picture_target']),
	);
	if ($tab['picture_id'] > 0) {
		unset($t['picture_order']);
		return _db_update('gallery_pic', $t, 'picture_id='.intval($tab['picture_id']));
	} else {
		return _db_insert('gallery_pic',$t);
	}
}

function gallery_image_del($gallery_id, $ord)
{
	$sql = 'DELETE FROM ' . DB_PREFIX . 'gallery_pic '
	. 'WHERE gallery_id = ' . intval($gallery_id)
	. ' AND picture_order = ' . intval($ord);
	if(!_db_query($sql))
	return false;
	$sql = 'UPDATE ' . DB_PREFIX . 'gallery_pic '
	. 'SET picture_order = picture_order - 1 '
	. 'WHERE gallery_id = ' . intval($gallery_id)
	. ' AND picture_order > ' . intval($ord);
	return _db_query($sql);
}

function gallery_picture_present($gallery_id, $image) {
	$sql = 'SELECT * FROM ' . DB_PREFIX . 'gallery_pic '
	. 'WHERE picture_file = ' . _db_string($image)
	. ' AND gallery_id = ' . _db_int($gallery_id);
	return _db_get_one($sql);
}

function gallery_picture_data($image, $gallery_id = 0) {
	$sql = 'SELECT picture_title, picture_description FROM ' . DB_PREFIX . 'gallery_pic '
	. 'WHERE picture_file = ' . _db_string($image)
	. ' AND (picture_title <> \'\' OR picture_description <> \'\')'
	. ' ORDER BY gallery_id != ' . intval($gallery_id) . ', gallery_id, picture_id LIMIT 1';
	return _db_get_one($sql);
}

function gallery_images_list($gallery_id, $limit = 0) {
	return _db_get('SELECT * FROM ' . DB_PREFIX . 'gallery_pic WHERE gallery_id = ' . intval($gallery_id)
	. ' ORDER BY picture_order'
	. ($limit > 0 ? ' LIMIT ' . intval($limit) : ''));
}

/**
 * Pobiera listę plików z galerii.
 * Domyślnie pobiera listę samych nazw plików. Po zaznaczeniu parametru $returnAll zwraca cały wiersz z tabeli.
 *
 * @param $gallery_id Id galerii
 * @param $limit Limit wierszy. Domyślnie 0.
 * @param $returnAll Znacznik czy zwracać całe wiersze z tabeli. Domyślnie false.
 *
 * @return array
 */
function gallery_images_files($gallery_id, $limit = 0,$returnAll = false)
{
	$res = _db_get('SELECT * FROM ' . DB_PREFIX . 'gallery_pic WHERE gallery_id = ' . intval($gallery_id)
	. ' ORDER BY picture_file'
	. ($limit > 0 ? ' LIMIT ' . intval($limit) : ''));
	$res2 = array();
	foreach($res as $tab) {
		if($returnAll)
		$res2[] = $tab;
		else
		$res2[] = $tab['picture_file'];
	}
	return $res2;
}

/**
 * Zwraca listę z nazwami wszystkich plików w bazie.
 * Są to pliki przypisane do galerii.
 *
 */
function gallery_images_files_all()
{
	$query = 'SELECT picture_file FROM ' . DB_PREFIX . 'gallery_pic' .
			' GROUP BY picture_file';
	$res = _db_get($query);
	$res2 = array();
	foreach($res as $tab) {
		$res2[] = $tab['picture_file'];
	}
	return $res2;
}

function gallery_picture_get($picture_id) {
	return _db_get_one('SELECT * FROM ' . DB_PREFIX . 'gallery_pic WHERE picture_id = ' . intval($picture_id));
}

function gallery_picture_reorder($oo, $no, $galleryId)
{
	return _db_reorder('gallery_pic','picture_order',$oo,$no,'gallery_id',$galleryId);
}

function gallery_readdir()
{
	global $GL_CONF;
	$cfg = $GL_CONF['IMAGES_FILES'];
	//echo $cfg['IMAGE_BASE_DIR'] . $cfg['IMAGE_DIR_1'].'j';
	$prem = '';
	if (file_exists('../'.$cfg['IMAGE_BASE_DIR'] . $cfg['IMAGE_DIR_1'])) {
		$prem = '../';
	}
	$dir = @opendir($prem . $cfg['IMAGE_BASE_DIR'] . $cfg['IMAGE_DIR_1']);
	if (!$dir) {
		return false;
	}
	$a = array();
	while (($file = readdir($dir)) !== false) {
		if (endsWith(strtolower($file), ".jpg") ||
		endsWith(strtolower($file), ".gif") ||
		endsWith(strtolower($file), ".png") ||
		endsWith(strtolower($file), ".jpeg")) {
			$a[] = $file;
		}
	}
	closedir($dir);
	// sortuje tablice
	sort($a);
	return $a;
}

function endsWith($s, $suffix) {
	return substr($s, strlen($s) - strlen($suffix)) == $suffix;
}

function gallery_vote_get($picture_file) {
	$sql = 'SELECT vote_id, picture_file, rank_count, rank_sum '
	. 'FROM ' . DB_PREFIX . 'gallery_votes '
	. 'WHERE picture_file = ' . _db_string($picture_file);
	$res = _db_get_one($sql);
	if(!$res)
	$res = array(
			'vote_id' => 0,
			'picture_file' => $picture_file,
			'rank_count' => 0,
			'rank_sum' => 0);
	return $res;
}

function gallery_vote($picture_file, $vote) {
	$tab = gallery_vote_get($picture_file);
	if(!$tab['vote_id']) {
		$sql = 'INSERT INTO ' . DB_PREFIX . 'gallery_votes '
		. '(picture_file, rank_count, rank_sum) '
		. 'VALUES (' . _db_string($picture_file) . ', 1, ' . _db_int($vote) . ')';
		return _db_query($sql);
	}
	$sql = 'UPDATE ' . DB_PREFIX . 'gallery_votes '
	. 'SET rank_count = rank_count + 1,'
	. 'rank_sum = rank_sum + ' . _db_int($vote)
	. ' WHERE picture_file = ' . _db_string($picture_file);
	return _db_query($sql);
}

function gallery_vote_max() {
	global $GL_CONF;
	$max = $GL_CONF['VOTE_MAX'];
	if(!$max)
	$max = 5;
	return $max;
}

function gallery_can_vote($picture_file) {
	$picture_file = ereg_replace('=', '', base64_encode($picture_file));
	return !isset($_COOKIE["vote_$picture_file"]);
}

function gallery_disallow_vote($picture_file) {
	$picture_file = ereg_replace('=', '', base64_encode($picture_file));
	setcookie("vote_$picture_file", true, time()+60*60*24);
	$_COOKIE["vote_$picture_file"] = true;
}

function gallery_get_menu_access($id)
{
	return _db_get('SELECT menu_id FROM `' . DB_PREFIX . 'mod_gallery_menu_access` WHERE gallery_id=' . intval($id));
}


/**
 * Sprawdza czy plik o danej nazwe istnieje w którymś z katalogów galerii.
 * Funkcja działa z poziomu panelu admina, dlatego scieżki mają początek ../
 *
 * @param unknown_type $name
 * @return unknown_type
 */
function gallery_image_exists($filename)
{
	global $GL_CONF;
	$cfg = $GL_CONF['IMAGES_FILES'];
	foreach ($cfg as $key => $value) {
		if (substr($key, 0, strlen('IMAGE_DIR_')) == 'IMAGE_DIR_') {
			$filepath = '../' . $cfg['IMAGE_BASE_DIR'] . $value . $filename;
			if (file_exists($filepath)) {
				return true;
			}
		}
	}
	$filepath =  '../' . $cfg['IMAGE_BASE_DIR'] . 'gallery/orig/' . $filename;
	if (file_exists($filepath)) {
		return true;
	}
	
	
	// gdy nie znaleziono pliku w galeriach zwraca false
	return false;
}
