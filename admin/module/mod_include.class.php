<?php
//if(defined('mod_text.class')) die('aa');
define('mod_include.class',1);

class mod_include {
	function update($tab) {
		return _db_replace('mod_include', array(
			'module_id' => _db_int($tab['module_id']),
			'include_type' => _db_int($tab['include_type']),
			'include_addr' => _db_string($tab['include_addr']),
		));
	}
	function remove($id) {
		return _db_delete('mod_include','module_id='.intval($id),1);
	}
	function validate($tab,$T) {
		return true;
	}
	function get($id) {
		return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_include` WHERE module_id='.intval($id).' LIMIT 1');
		
	}
	function front($module,$Item) {
		$data = $this->get($module['module_id']);
		if(!$data || !$data['include_addr'])
			return;
		$type = $data['include_type'];
		if($type == 0) {
			include $data['include_addr'];
		} else {
			readfile($data['include_addr']);
		}
	}
}
