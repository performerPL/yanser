<?php
define('mod_subscription.class', 1);
require_once 'module/Bean.class.php';

class mod_subscription extends Mod_Bean {
	
    /* typ modułu */
    private $moduleType = 36;

	public function update($tab)
	{
		return _db_replace('mod_subscription',
		array(
        'module_id'=>_db_int($tab['module_id']),
        'show_author'=>_db_int($tab['show_author']),
        'show_source'=>_db_int($tab['show_source']),
        'show_date_create'=>_db_int($tab['show_date_create']),
        'show_date_update'=>_db_int($tab['show_date_update']),
		));
	}

	public function remove($id)
	{
		return _db_delete('mod_subscription', 'module_id='.intval($id),1);
	}

	public function validate($tab, $T)
	{
		return true;
	}

	public function get($id)
	{
		return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_subscription` WHERE module_id='.intval($id).' LIMIT 1');
	}

    /**
     * Kontroler wyświetlający dane modułu w przegladarce.
     * Kod HTML znajduje się w szablonie Smarty "mod_subscription/subscription.html".
     *
     * @param $module
     * @param $Item
     * @return unknown_type
     */
    public function front($module, $Item)
    {
        // uaktualnia dane o module (dla ustawień ręcznych)
        $module = $this->getModuleContent($module['module_id'],$module);
        
        // pobiera dane modułu z bazy
        $data = $this->get($module['module_id']);

        // styl przypisany do modułu
        $style = $module['module_style'];

                        
        /*****************************/
        /** SMARTY **/
        /*****************************/
        $out = array();
        $out[data] = $data;
        $out[module] = $module;
        $out[Item] = $Item;
        
        // załącza tablicę z parametrami
        $this->smarty->assign('out',$out);
        // wyświetla listę
        $this->smarty->display("mod_subscription/subscription.html");
    }
}
