<?php
define('mod_show_www_catalog_top.class', 1);

class mod_show_www_catalog_top
{
	function update($tab)
	{
		return _db_replace('mod_show_www_catalog_top',  array(
		'module_id'=>_db_int($tab['module_id']),
		'style'=>_db_int($tab['style']),
		'row_limit'=>_db_int($tab['row_limit']),
		'www_catalog_group_id'=>_db_int($tab['www_catalog_group_id']),
		'show_title'=>_db_bool($tab['show_title']),
        'show_description'=>_db_bool($tab['show_description']),
        'show_url'=>_db_bool($tab['show_url']),
		));
	}

	function remove($id)
	{
		return _db_delete('mod_show_www_catalog_top', 'module_id='.intval($id), 1);
	}

	function validate($tab, $T)
	{
		return true;
	}

	function get($id)
	{
		$returnData = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_show_www_catalog_top` WHERE module_id=' . intval($id) . ' LIMIT 1');
		// grupy główne
		$returnData['topGroups'][0] = '';
		$tmp = _db_get('SELECT * FROM `' . DB_PREFIX . 'www_catalog_group` WHERE parent_id = 0');
		foreach($tmp as $row) {
			$returnData['topGroups'][$row['id']] = $row['name'];
		}
		return $returnData;
	}


	function front($module, $Item)
	{

	}
}
