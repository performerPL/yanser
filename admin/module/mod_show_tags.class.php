<?php
define('mod_code_block.class', 1);
require_once '../module/Bean.class.php';
require_once '../lib/article.php';

class mod_show_tags extends Mod_Bean {
	
    /* typ modułu */
    private $moduleType = 40;

	/* scieżka względna */
	protected $includePath = '../';

	public function update($tab)
	{
		return _db_replace('mod_tag',
		array(
        'module_id'=>_db_int($tab['module_id']),
        'style'=>_db_int($tab['style']),
		'limit'=>_db_int($tab['limit']),
		'show_alphabetically'=>_db_int($tab['show_alphabetically']),
		'show_popularity'=>_db_int($tab['show_popularity']),
		'show_hits'=>_db_int($tab['show_hits']),
		'search_article_id'=>_db_int($tab['search_article_id']),
		));
	}

	public function remove($id)
	{
		return _db_delete('mod_tag', 'module_id='.intval($id),1);
	}

	public function validate($tab, $T)
	{
		return true;
	}

	public function get($id)
	{
		$row =  _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_tag` WHERE module_id='.intval($id).' LIMIT 1');
		return $row;
	}

}
