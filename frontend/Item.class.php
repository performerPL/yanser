<?php
if (!defined('_APP')) {
	exit;
}

require_once 'lib/item.php';
require_once 'lib/article.php';

class Item
{
	private
	$simple = false,
	$tab = array(),
	$hist = false,
	$subs = false,
	$page = 0,
	$url = '';

	function __construct($item, $tab=false, $page=0, $path='')
	{
		global $Site;
		if ($tab) {			
			$this->tab = $item;
			$this->simple = true;
		} else {
			// gdy zmienna $item jest tablicą, nadpisujemy ja item_id
			if(is_array($item)) {
				$item = $item[item_id];
			}
			
			if ($item > 0) {
				$this->page = $page;
				$this->url = $path;
				$this->tab = item_get($item);	
						
				//COUNTER START
				// sprawdza czy artykuł nie był już klikany oraz czy to nie bot
				if (empty($_COOKIE['article_' . $item]) && !$this->detectBot()) {
					if (!headers_sent()) {
						setcookie("article_" . $item, "1", time() + LICZNIK_ART_EXPIRE);
						_db_query("UPDATE " . DB_PREFIX . "article SET counter=counter+1 WHERE article_id=" . intval($this->tab['article']['article_id']) . " LIMIT 1");
					}
				}
				//COUNTER END
				//echo $path;
				//var_dump($item);
				//echo $Site->urlize($this->getUrl());
				/*				if($Site->urlize($this->getUrl()) != $path ) {
				 header('HTTP/1.0 404 Not Found');
				 echo 'File not found.';
				 die();
				 //exit;
				 }*/
				//pobierz historię - od parenta do 
				$this->hist = new ItemList(item_get_history($this->getParentID()));

				//pozmieniaj addonsy - idąc od parenta do góry
				$go = array();
				for ($j=0; $j < $this->getHistory()->getCount() && count($go) != ADDONS_COUNT; ++$j) {
					//foreach($this->hist->getArray() as $level =>$par) {
					$par = $this->hist->get($j);
					for ($i=0; $i < ADDONS_COUNT; ++$i) {
						if ($this->tab['addon']['add'.$i] == '') {
							$re = $this->tab['addon']['add' . $i] = $this->hist->get($j)->getAddon($i);
							if ($re!='') {
								$go[$i] = false;
							}
						} else {
							$go[$i] = false;
						}
					}
				}

				//pobierz subitemy
				//$this->subs = new Subitems($item_id);
			}
		}
		$this->getParentAddon(1);
	}

	
	function getHistoryLine() {
				$ree = $this->hist = new ItemList(item_get_history($this->getParentID()));
				$res_count = $this->getHistory()->getCount();
				//print_r($ree);
				$res = array();
				$res[($res_count+1)] = '<span class="current">'.$this->getName().'</span>';
				for ($j=0; $j < $res_count; ++$j) {
					//foreach($this->hist->getArray() as $level =>$par) {
					//$res[$j] = $this->hist->get($j)->getUrl();
					$res[$j] = '<a href='.$this->hist->get($j)->getLinkUrl().'>'.$this->hist->get($j)->tab['item_name'].'</a> <span class="visibility"> / </span>';
					//$re = $this->tab['addon']['add' . $i] = $this->hist->get($j)->getAddon($i);
					//$re = $this->tab['item_name'];
					//$re = $this->tab[$j]['item_name'];
					//return  $this->hist->get($j)->tab['item_name'];
					//return $re;
				}
				$wynik   = array_reverse($res);
				return $wynik;
	}
	
	
	
	function getParentID()
	{
		return isset($this->tab['parent_id']) ? intval($this->tab['parent_id']) : -1;
	}

	function getIDName($id_name)
	{
		return ($id_name->tab['item_name']);
	}
	function getMenuID()
	{
		return $this->tab['menu_id'];
	}

	function getParent($level)
	{
		if ($level==0) {
			return $this;
		} else {
			if ($level > 0) {
				return $this->hist->get($level - 1);
			} else {
				return $this->hist->get($this->hist->getCount() + $level);
			}
		}
	}

	function isShowable()
	{
		return ($this->tab['item_type'] != ITEM_LINK_OUT);
	}

	public function getAuthorSource()
	{
		if ($this->tab['article']['author_source'] != '') {
			if ($this->tab['article']['author_source_name'] != '') {
				$name = $this->tab['article']['author_source_name'];
			} else {
				$name = $this->tab['article']['author_source'];
			}

			return '<a href="' . $this->tab['article']['author_source'] . '" class="link_source" target="_blank">' . $name . '</a>';
		}
	}

	public function isShowAuthor()
	{
		return (bool) $this->tab['show_author'];
	}

	public function isShowDate()
	{
		return (bool) $this->tab['show_created'];
	}

	public function isShowMod()
	{
		return (bool) $this->tab['show_modificated'];
	}

	public function isShowInSubitems()
	{
		if ($this->tab['hide_in_subitems']) {
			return false;
		}
		return true;
	}

	public function isShowInMenu()
	{
		if ($this->tab['hide_in_menu']) {
			return false;
		}
		return true;
	}

	public function isShowInMap()
	{
		if ($this->tab['hide_in_map']) {
			return false;
		}
		return true;
	}

	private function _translateAddonKey($string)
	{
		$q = "SELECT c.config_code FROM " . DB_PREFIX . "config c, " . DB_PREFIX . "config_value cv WHERE c.config_id IN (11,13,14,15,16,17,18,19,20,21) "
		. "AND c.config_id=cv.config_id AND cv.config_value=" . _db_string($string);
		$R = _db_get_one($q);
		if ($R === false) {
			return null;
		}

		return (int) str_replace(array('ADDON', 'NAME'), '', $R['config_code']);
	}

	public function getAddon($i, $parent=false)
	{
		if (is_string($i)) {
			$i = $this->_translateAddonKey($i);
		}
		if ($parent == true) {
			if ($this->tab['addon']['add' . intval($i)]=='') {
				return $this->getParentAddon($i);
			}
		} else {
			return $this->tab['addon']['add' . intval($i)];
		}
	}

	public function getAuthor()
	{
		$q = "SELECT author FROM " . DB_PREFIX . "article WHERE article_id=" . $this->tab['article_id'];
		$X = _db_get_one($q);
		return $X['author'];
	}

	public function getDate()
	{
		return $this->tab['created'];
	}

	public function getDateStart()
	{
		return $this->tab['show_start'];
	}

	public function getDateMod()
	{
		return $this->tab['modificated'];
	}

	function getParentAddon($i)
	{
		$parent = item_get($this->getParentID());
		return $parent['addon']['add' . intval($i)];
	}

	function getTemplateID()
	{
		return $this->tab['article']['template_id'];
	}

	function getHistory()
	{
		if (!is_object($this->hist)) {
			$this->hist = new ItemList;
		}
		return $this->hist;
	}

	function isActive()
	{
		return ($this->tab['active'] > 0) ? true : false;
	}

	/**
	 * funkcja zwracajaca liczbe podstron
	 * @return int Ilość stron
	 */
	public function getCountChild()
	{
		return count(item_get_orders($this->getID()));
	}

	function getID()
	{
		return intval($this->tab['item_id']);
	}

	function getUrl()
	{
		return $this->tab['item_code'] != '' ? $this->tab['item_code'] : $this->tab['item_long_name'];
	}

	function getName()
	{
		return $this->tab['item_name'];
	}

	function getNameID($site_id)
	{
		return $site_id->tab['item_name'];
	}

	function getMTitle()
	{
		return $this->tab['item_meta_title'];
	}

	function getTitle($long=false)
	{
		global $Site;
		if (is_array($this->tab)) {
			return ($long &&$this->tab['item_long_name'] != '') ? $this->tab['item_long_name'] : $this->tab['item_name'];
		} else {
			return $Site->getTitle();
		}
	}

	function getLongName()
	{
		return $this->tab['item_long_name'] != '' ? $this->tab['item_long_name'] : $this->tab['item_name'];
	}
    
	/**
	 * Pobiera opis itema.
	 * 
	 * @param $cutEnd gdy true ucina końcówkę znacznika </p>
	 * @return string
	 */
	function getDescription($cutEnd = false)
	{
		if($cutEnd) {
			 $trimDesc = trim($this->tab['item_description']);
             // ucina znacznik </p> na końcu opisu - należy go dodać po przylaczeniu elementów ktore maja sie znalezc w paragrafie
             return $description = substr($trimDesc,0,strlen($trimDesc)-4);
		}
		else {
		  return $this->tab['item_description'];	
		}
	}

	function getIcon()
	{
		return $this->tab['item_icon'];
	}

	function getAccessLevel()
	{
		return $this->tab['access_level'];
	}

	function getMetaDescription()
	{
		global $Site;
		if (is_array($this->tab['article'])) {
			return $this->tab['article']['meta_description'];
		} else {
			return $Site->getMetaDescription();
		}
	}

	function getMetaKeywords()
	{
		global $Site;
		if (is_array($this->tab['article'])) {
			return $this->tab['article']['meta_keywords'];
		} else {
			return $Site->getMetaKeywords();
		}
	}

	function getPublishDate()
	{
	}

	function getGroups()
	{
	}

	function getContent()
	{
		global $GL_MOD_TYPE;

		if (is_array($this->tab['article']['content'])) {
			//echo var_dump($this->tab['article']['content']);
			foreach ($this->tab['article']['content'] as $k => $module) {
				if (!$module['active']) {
					continue;
				}

				//echo $module['module_name'].'<br />';
				//article_mod_call($module['module_type'],'front',$module['module_id'],$this);
				//article_mod_call($tab['module_type'],'update',$tab);
				$type = $GL_MOD_TYPE[$module['module_type']]->script;

				if (file_exists('module/' . $type . '.class.php')) {
					require_once 'module/' . $type . '.class.php';
					if (class_exists($type)) {
						$c = new $type();
						$c->front($module, $this);
					}
				} else {
					//echo 'ni ma';
				}
			}
		} else {
			echo 'no content';
		}
	}

	public function getMapLink($content='', $options = array())
	{
		global $Site;

		$res = '';
		switch ($this->getItemType()) {
			case ITEM_LINK_OUT:
				$res .= $this->tab["link_url"];
				break;

			case ITEM_LINK_IN:
				$_i = new Item($this->tab["target_id"]);
				$res .= $Site->getUrl($_i);
				break;

			default:
				$res .= $Site->getUrl($this);
		}


		return $res;
	}

	function getLink($content='', $options=array(), $current = false, $id = '')
	{
		global $Site;

		$res = '<a href="';
		switch ($this->getItemType()) {
			case ITEM_LINK_OUT:
				$pref = '';
				if (defined('ABSOLUTE_URLS')) {
					$pref = MAIN_DOMAIN;
				}
				$res .= $pref . $this->tab["link_url"] . '" ';
				break;

			case ITEM_LINK_IN:
				$pref = '';
				if (defined('ABSOLUTE_URLS')) {
					$pref = MAIN_DOMAIN;
				}
				$_i = new Item($this->tab["target_id"]);
				$res .= $pref . $Site->getUrl($_i) . '" ';
				break;

			default:
				$pref = '';
				if (defined('ABSOLUTE_URLS')) {
					$pref = MAIN_DOMAIN;
				}
				$res .= $pref . $Site->getUrl($this).'" ';
		}
		foreach ($options as $k=>$v) {
			if($k!='href' && $k!='title' && $k!='target') {
				$res .= $k.'="'.$v.'" ';
			}
		}
		$res .= ' title="'.$this->getTitle(true).'" ';
		if(($this->getItemType()==ITEM_LINK_OUT || $this->getItemType()==ITEM_LINK_IN) && $this->getLinkTarget()==LINK_TARGET_BLANK) {//ustawienie targetu
			$res .= ' target="_blank" ';
		}
		$res .= 'class=" item_'.$id;
		
		if ($current == true) {
			$res .= ' current ';
		}
		$res .= ' ">';
		
		if($content!='') {
			$res .= $content;
		} else {
			$res .= $this->getTitle();
		}
		$res .= '</a>';
		return $res;
	}

	function getItemType()
	{
		return $this->tab['item_type'];
	}

	function getLinkTarget()
	{
		return $this->tab['link_target'];
	}

	function getLinkUrl($content='',$options=array())
	{

		global $Site;

		$res = '"';
		switch ($this->getItemType()) {
			case ITEM_LINK_OUT:
				$pref = '';
				if (defined('ABSOLUTE_URLS')) {
					$pref = MAIN_DOMAIN;
				}
				$res .= $pref . $this->tab['link_url'] . '" ';
				break;

			case ITEM_LINK_IN:
				$pref = '';
				if (defined('ABSOLUTE_URLS')) {
					$pref = MAIN_DOMAIN;
				}
				$_i = new Item($this->tab['target_id']);
				$res .= $pref . $Site->getUrl($_i) . '" ';
				break;

			default:
				$pref = '';
				if (defined('ABSOLUTE_URLS')) {
					$pref = MAIN_DOMAIN;
				}
				$_i = new Item($this->tab['target_id']);
				$res .= $pref . $Site->getUrl($this) . '" ';
				break;
		}

		foreach ($options as $k=>$v) {
			if ($k != 'href' && $k != 'title' && $k != 'target') {
				$res .= $k . '="' . $v . '" ';
			}
		}
		$res .= ' alt="' . $this->getTitle(true) . '" ';
		if (($this->getItemType()==ITEM_LINK_OUT || $this->getItemType()==ITEM_LINK_IN) && $this->getLinkTarget()==LINK_TARGET_BLANK) {//ustawienie targetu
			$res .= ' target="_blank" ';
		}

		$res .= '';
		/*
		 if($content!='') {
		 $res .= $content;
		 } else {
		 $res .= $this->getTitle();
		 }
		 $res .= '</a>';*/
		//echo $res . "<br/>";
		return $res;
	}

	/*
	 function getSubitems() {
	 if(!is_object($this->subs)) {
	 $this->subs = new ItemList();
	 }
	 return $this->subs;
	 }
	 */
	function isSimple()
	{
		return $this->simple;
	}

	/**
	 * Metoda zwraca priorytet dla artykulu.
	 *
	 * @return float Priorytet artykułu.
	 */
	function getArticleOrder() {
		if(empty($this->tab['article']['order']) || $this->tab['article']['order'] == '0.00')
			return 0.6;
		else	
			return $this->tab['article']['order'];
	}

	/**
	 * Metoda zwraca licznik popularności artykułu.
	 * 
	 * @return int Licznik popularności
	 */
	function getArticleCounter() {
		return $this->tab['article']['counter'];
	}
	
	public function detectBot() {
		global $BOT_LIST;
		
		foreach($BOT_LIST as $bot) {
			//if(ereg($bot, $_SERVER['HTTP_USER_AGENT'])) { 
			if(preg_match("/$bot/", $_SERVER['HTTP_USER_AGENT'])) { 
				return true;
			}
		}
		
		return false;
	}
}
