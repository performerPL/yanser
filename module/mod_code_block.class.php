<?php
define('mod_code_block.class', 1);
require_once 'module/Bean.class.php';
require_once 'lib/code_blocks.php';

class mod_code_block extends Mod_Bean {
    
    /* typ modułu */
    private $moduleType = 37;

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
        $out[codeBlock] = code_block_get($module['code_block_id']);
        
        // załącza tablicę z parametrami
        $this->smarty->assign('out',$out);
        // wyświetla listę
        $this->smarty->display("mod_code_block/code.html");
    }
}
