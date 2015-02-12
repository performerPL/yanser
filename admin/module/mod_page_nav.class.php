<?php
define('mod_code_block.class', 1);
require_once '../module/Bean.class.php';
require_once '../lib/article.php';

class mod_page_nav extends Mod_Bean {
    
    /* typ modułu */
    private $moduleType = 15;

    /* scieżka względna */
    protected $includePath = '../';

    public function update($tab)
    {
        return _db_replace('mod_page_nav',
        array(
        'module_id'=>_db_int($tab['module_id']),
        ));
    }

    public function remove($id)
    {
        return _db_delete('mod_page_nav', 'module_id='.intval($id),1);
    }

    public function validate($tab, $T)
    {
        return true;
    }

    public function get($id)
    {
        $row =  _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_page_nav` WHERE module_id='.intval($id).' LIMIT 1');
        return $row;
    }

}
