<?php
define('mod_shownotices_top.class', 1);

require_once 'module/mod_shownotices_tree.class.php';
require_once 'module/Bean.class.php';


class mod_shownotices_top extends Mod_Bean
{
	/**
	 * domyślnie pokazuję 10 wierszy
	 */
	private $rowLimit = 10;
	
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
		return _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_shownotices_top` WHERE module_id=' . intval($id) . ' LIMIT 1');
	}

	/**
	 * 
	 * @param $rowLimit Limit pobieranych wierszy
	 * @param $groupId Filtrowana grupa
	 * @return Pobiera listę 10 najnowszych aktywnych ogłoszeń.
	 */
	function fetchNotices($rowLimit = 10,$groupId = 0)
	{
        $sql = "SELECT n.*, ngm.ngm_id, ngm.ngm_name,ngi.ng_id FROM " . DB_PREFIX . 
		"notice n LEFT JOIN " . DB_PREFIX . "notice_group_in ngi ON (n.n_id=ngi.n_id) JOIN " . DB_PREFIX . "notice_group_main ngm ON (ngi.ngm_id=ngm.ngm_id)".
    	" WHERE n.n_status = 1 ";
        if(!empty($groupId)) 
        {
        	// warunek dla grup i podgrup
        	$sql .= " AND (ngi.ng_id = " . $groupId . " OR  ngi.ng_id IN (SELECT ng_id FROM " . DB_PREFIX . "notice_group WHERE ng_parent_id = " . $groupId . ") )";
        }
        
		$sql .= " GROUP BY ngi.n_id ORDER BY n_priority DESC, n.n_created DESC LIMIT " . $rowLimit;
		$RES = _db_get($sql);
		
		$NOTICES = array();
	    if (is_array($RES)) {
            foreach ($RES as $k => $V) {
                // dodaje pole n_title_urlized
                $V[n_title_urlized] = Site :: urlize($V[n_title]);
                $NOTICES[] = $V;
            }
        }
		
		return $NOTICES;
	}

	function front($module, $Item)
	{
	    // uaktualnia dane o module (dla ustawień ręcznych)
        $module = $this->getModuleContent($module['module_id'],$module);

        // pobiera dane modułu z bazy
        $data = $this->get($module['module_id']);
        if (!$data) {
//            return;
        }
		
		$mod_shownotices_tree = new mod_shownotices_tree();
		$GROUPS = array();
        $GROUPS = $mod_shownotices_tree->fetchGroups($GROUPS);
        
        // nadpisuje limit wyświetlanych wierszy gdy liczba większa od zera
        if($module['row_limit'] > 0)
            $this->rowLimit = $module['row_limit'];

        $NOTICES = $this->fetchNotices($this->rowLimit,$module['notice_group_id']);
        
	    foreach ($NOTICES as $k => &$V) {
            $V[groupPathUrlized] = $mod_shownotices_tree->displayGroupPathUrlized($GROUPS,$V['ng_id']);            
        }

        /***********************************/
        /** SMARTY - lista ogłoszeń       **/
        /***********************************/

        $out = array();
        $out['notices'] = $NOTICES;
        $out['topGroups'] = $topGroups;

        // załącza tablicę z parametrami
        $this->smarty->assign('out',$out);
        // wyświetla listę
        $this->smarty->display("mod_shownotices_top/notices_list.html");

	}
}
