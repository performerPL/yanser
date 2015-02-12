<?php
define('mod_show_www_catalog_top.class', 1);

require_once 'module/mod_show_www_catalog_tree.class.php';
require_once 'module/Bean.class.php';


class mod_show_www_catalog_top extends Mod_Bean
{
	/**
	 * domyślnie pokazuję 10 wierszy
	 */
	private $rowLimit = 10;

	function update($tab)
	{
		return _db_replace('mod_show_www_catalog_top', array('module_id'=>_db_int($tab['module_id']),'style'=>_db_int($tab['style']),'row_limit'=>_db_int($tab['row_limit']),'www_catalog_group_id'=>_db_int($tab['www_catalog_group_id'])));
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
		return _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_show_www_catalog_top` WHERE module_id=' . intval($id) . ' LIMIT 1');
	}

	/**
	 *
	 * @param $rowLimit Limit pobieranych wierszy
	 * @param $groupId Filtrowana grupa
	 * @return Pobiera listę 10 najnowszych aktywnych stron.
	 */
	function fetchSites($rowLimit = 10,$groupId = 0)
	{
		$sql = "SELECT wc.*, wcgi.www_catalog_group_id FROM " . DB_PREFIX .
		"www_catalog wc LEFT JOIN " . DB_PREFIX . "www_catalog_group_in wcgi ON (wc.id=wcgi.www_catalog_id)" .
    	" WHERE wc.active = 1 ";
		if(!empty($groupId))
		{
			// warunek dla grup i podgrup
			$sql .= " AND (wcgi.www_catalog_group_id = " . $groupId . " OR  wcgi.www_catalog_group_id IN (SELECT www_catalog_group_id FROM " . DB_PREFIX . "www_catalog_group WHERE parent_id = " . $groupId . ") )";
		}

		$sql .= " GROUP BY wc.id ORDER BY title ASC LIMIT " . $rowLimit;
		$RES = _db_get($sql);

		$SITES = array();
		if (is_array($RES)) {
			foreach ($RES as $k => $V) {
				// dodaje pole title_urlized
				$V['title_urlized'] = Site :: urlize($V['title']);
				$SITES[] = $V;
			}
		}

		return $SITES;
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

		$mod_show_www_catalog_tree = new mod_show_www_catalog_tree();
		$GROUPS = array();
		$GROUPS = $mod_show_www_catalog_tree->fetchGroups($GROUPS);

		// nadpisuje limit wyświetlanych wierszy gdy liczba większa od zera
		if($module['row_limit'] > 0)
		$this->rowLimit = $module['row_limit'];

		$SITES = $this->fetchSites($this->rowLimit,$module['www_catalog_group_id']);

		foreach ($SITES as $k => &$V) {
			$V[groupPathUrlized] = $mod_show_www_catalog_tree->displayGroupPathUrlized($GROUPS,$V['www_catalog_group_id']);
		}

		/***********************************/
		/** SMARTY - lista ogłoszeń       **/
		/***********************************/

		$out = array();
		$out['data'] = $data;
		$out['sites'] = $SITES;
		$out['topGroups'] = $topGroups;

		// załącza tablicę z parametrami
		$this->smarty->assign('out',$out);
		// wyświetla listę
		$this->smarty->display("mod_show_www_catalog_top/www_catalog_list.html");

	}
}
