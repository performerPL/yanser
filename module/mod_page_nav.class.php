<?php
define('mod_page_nav.class', 1);

require_once 'module/Bean.class.php';


class mod_page_nav extends Mod_Bean
{

	/* typ modułu */
	private $moduleType = 15;

	public function update($tab)
	{
		return _db_replace('mod_page_nav',
		array(
        'module_id'=>_db_int($tab['module_id']),
		)
		);
	}

	public function remove($id)
	{
		return _db_delete('mod_page_nav', 'module_id='.intval($id), 1);
	}

	public function validate($tab, $T)
	{
		return true;
	}

	public function get($id)
	{
		return _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_page_nav` WHERE module_id=' . intval($id) . ' LIMIT 1');
	}


	/**
	 * Szuka dla parenta listy itemów.
	 * Jeśli w parencie jest moduł subitems, pobiera z niego rodzaj sortowania. 
	 *
	 * @param unknown_type $parentId
	 * @return unknown_type
	 */
	private function getEquivalentItems($parentId) {
		 
		// pobiera ustawienia z modulu subitems z parenta
		$parent = article_mod_get_by_type(item_get_article_id($parentId),6);

		switch ($parent['show_sort_type']) {
			case 1 : // dla 1 sortowanie po dacie utworzenia
				$sb = 'i.created DESC';
				break;
			case 3 : // dla 1 sortowanie po dacie utworzenia - rosnąco
				$sb = 'i.created ASC';
				break;

			case 2 : // dla 2 sortowanie po liczniku ogladalnosci
				$sb = 'a.counter DESC';
				break;
			case 4 : // dla 2 sortowanie po liczniku ogladalnosci - rosnąco
				$sb = 'a.counter ASC';
				break;

			case 0 : // dla zera sortowanie po numerze porządkowym
			default:
				$sb = 'i.item_order';
				break;
		}

		$data = array();
		$subitems = item_get_orders($parentId, 0, $sb, true, 'item_id');
		foreach ($subitems as $k => &$subitem) {
			$subitem = new Item($subitem);
			// usuwa itemy nieaktywne, lub takie ktore są linkami
			if ( !$subitem->isActive() || ( ($subitem->getItemType() != 0) && ($subitem->getItemType() != 1)) ) {			
				continue;
			}
			$data[$k] = $subitem;
		}

		return $data;
	}

	/**
	 * Kontroler wyświetlający dane modułu w przegladarce.
	 * Kod HTML znajduje się w szablonie Smarty "mod_page_nav/nav.html".
	 *
	 * @param $module
	 * @param $Item
	 * @return unknown_type
	 */
	public function front($module, Item $Item)
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
		$out['data'] = $data;
		$out['styles'] = $this->styles;
		$out['currentStyle'] = $this->styles[$style];
		$out['module'] = $module;
		$out['mod_style'] = $style;
		$list = $this->getEquivalentItems($Item->getParentID());
		foreach($list as $subitemId => $subitem) {		
			if($subitemId == $Item->getID()) {
				$next = prev($list);
				$next = next($list);
					if(!empty($next) && $next->getID() != $Item->getID()) {
					$out['next'] = $next->getLink('Następna strona',array('alt' => $next->getTitle(), 'class' =>'btn'));
				}
			}
		}
		$listA = $this->getEquivalentItems($Item->getParentID());
		// foreach($listA as $subitemId => $subitem) {	
			// if($subitemId == $Item->getID()) {
				// $next = prev($listA);
				// $next = next($listA);
				// $prev = prev($listA);
				// $prev = prev($listA);
				// if(!empty($prev) && $prev->getID() != $Item->getID()) {
					// $out['prev'] = $prev->getLink('Poprzedni',array('alt' => $prev->getTitle()));
				// }
			// }
		// }
				
		
		foreach($listA as $subitemId => $subitem) {	
			$tab[] = $subitemId;
		}
		$szukaj = array_search($Item->getID(), $tab);
		$prev = $listA[$tab[$szukaj-1]];
		if(!empty($prev))
		{
			$out['prev'] = $prev->getLink('Poprzednia strona',array('alt' => $prev->getTitle(), 'class' =>'btn'));
		}

		// załącza tablicę z parametrami
		$this->smarty->assign('out',$out);
		// wyświetla listę
		$this->smarty->display("mod_page_nav/nav.html");
	}
}
