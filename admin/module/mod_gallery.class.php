<?php
//if(defined('mod_text.class')) die('aa');
define('mod_gallery.class',1);

class mod_gallery {

	function update($tab) {
		return _db_replace('mod_gallery', array(
			'module_id' => _db_int($tab['module_id']),
			'gallery_id' => _db_int($tab['gallery_id']),
			'show_title' => _db_int($tab['show_title']),
			'show_description' => _db_int($tab['show_description']),
			'show_gallery_description' => _db_int($tab['show_gallery_description']),
			'show_target_url' => _db_int($tab['show_target_url']),
		    'show_enlarge' => _db_int($tab['show_enlarge']),
		    'show_enlarge_lightbox' => _db_int($tab['show_enlarge_lightbox']),
		    'show_pictures_counter' => _db_int($tab['show_pictures_counter']),
		    'image_type' => _db_string($tab['image_type']),
		    'show_gallery_name' => _db_int($tab['show_gallery_name']),
		));
	}
	function remove($id) {
		return _db_delete('mod_gallery','module_id='.intval($id),1);
	}
	function validate($tab,$T) {
		return $tab['gallery_id'] > 0;
	}
	function get($id) {
		return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_gallery` WHERE module_id='.intval($id).' LIMIT 1');	
	}
	
}
