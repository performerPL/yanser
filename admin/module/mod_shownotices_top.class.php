<?php
define('mod_shownotices_top.class', 1);

class mod_shownotices_top
{
	function update($tab)
	{
		return _db_replace('mod_shownotices_top', array('module_id'=>_db_int($tab['module_id']),'style'=>_db_int($tab['style']),'row_limit'=>_db_int($tab['row_limit']),'notice_group_id'=>_db_int($tab['notice_group_id'])));
	}

	function remove($id)
	{
		return _db_delete('mod_shownotices_top', 'module_id='.intval($id), 1);
	}

	function validate($tab, $T)
	{
		return true;
	}

	function get($id)
	{
		$returnData = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_shownotices_top` WHERE module_id=' . intval($id) . ' LIMIT 1');
		// grupy główne
		$returnData['topGroups'][0] = '';
		$tmp = _db_get('SELECT * FROM `' . DB_PREFIX . 'notice_group` WHERE ng_parent_id = 0');
		foreach($tmp as $row) {
			$returnData['topGroups'][$row['ng_id']] = $row['ng_name'];
		}
		return $returnData;
	}


	function front($module, $Item)
	{

	}
}
