<?php
define('mod_code_block.class', 1);
require_once '../module/Bean.class.php';
require_once '../lib/code_blocks.php';

class mod_code_block extends Mod_Bean {
	
    /* typ modułu */
    private $moduleType = 37;

	/* scieżka względna */
	protected $includePath = '../';

	public function update($tab)
	{
		return _db_replace('mod_code_block',
		array(
        'module_id'=>_db_int($tab['module_id']),
        'code_block_id'=>_db_int($tab['code_block_id']),
		));
	}

	public function remove($id)
	{
		return _db_delete('mod_code_block', 'module_id='.intval($id),1);
	}

	public function validate($tab, $T)
	{
		return true;
	}

	public function get($id)
	{
		$row =  _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_code_block` WHERE module_id='.intval($id).' LIMIT 1');
		$row['code_blocks'] = $this->getSimpleArray(array_merge(array(0 => array('name' => "")),code_block_list(true)));
		
		return $row;
	}

}
