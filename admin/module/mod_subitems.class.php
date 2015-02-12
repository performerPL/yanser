<?php
//if(defined('mod_subitems.class')) die('aa');
define('mod_subitems.class', 1);

class mod_subitems
{
	/* tablica z typami sortowania */
	private $sortTypeList = array(
		0 => "Wg kolejności",
		1 => "Wg daty dodania(malejąco)",
		3 => "Wg daty dodania(rosnąco)",
		2 => "Wg oglądalności(malejąco)",
		4 => "Wg oglądalności(rosnąco)",
	);

	/* typ sortowania - DOMYŚLNIE */
	private $defaultSortType = 1;
	
	/* typ sortowania */
	private $sortType;

	
	/**
	 * Konstruktor.
	 * 
	 * @param $sortType
	 * @return unknown_type
	 */
	public function __construct($sortType = 0) {
		$this->sortType = $sortType;
	}

	function update($tab)
	{
		// sprawdza zmienną sort_type, gdy nie isnieje ustawia ją na wartość domyślną
		if(!isset($tab['show_sort_type']))
			$tab['show_sort_type'] = $this->defaultSortType;
		
		$q = array(
			'subitems_id'=>_db_int($tab['module_id']),
			'show_icon'=>_db_bool($tab['show_icon']),
			'show_title'=>_db_bool($tab['show_title']),
			'show_description'=>_db_bool($tab['show_description']),
			'show_date'=>_db_bool($tab['show_date']),
    		'show_date_mod'=>_db_bool($tab['show_date_mod']),
			'show_author'=>_db_int($tab['show_author']),
			'show_per_page'=>_db_int($tab['show_per_page']),
            'show_sort' => _db_int($tab['show_sort']),
    		'show_sort_type' => _db_int($tab['show_sort_type']),
			'show_popularity' => _db_int($tab['show_popularity']),
			'show_content' => _db_int($tab['show_content']),
		    'show_article_id' => _db_int($tab['show_article_id']),  
		    'show_subitems_counter' => _db_int($tab['show_subitems_counter']),  
		);
		return _db_replace('mod_subitems', $q);
	}

	function remove($id)
	{
		return _db_delete('mod_subitems','subitems_id='.intval($id),1);
	}

	function validate($tab, $T)
	{
		return true;
	}

	function get($id)
	{
		return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_subitems` WHERE subitems_id='.intval($id).' LIMIT 1');
	}

	function front($module, $Item)
	{
		global $GL_CONF;
		$cfg = $GL_CONF['IMAGES_FILES'];
		//		$subitems = new Menu('',$Item->getID(),1);
		//		$subitems->printList();
		$options = $this->get($module["module_id"]);
		$subitems = item_get_orders($Item->getID());
		$style = $module['module_style'];

		switch ($style) {
			case 0: // ikona po lewej, tytu�, zajawka
				echo '<ul class="subitems_0">';
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					echo '<li>';
					if ($subitem->getIcon() != '') {
						echo '<div class="sub_icon">';
						echo '<a href='.$subitem->getLinkUrl().'>';
						echo '<img src="' . $cfg['IMAGE_BASE_URL'] .$subitem->getIcon().'" border="0" /></a></div>';
					}
					echo '<div class="title">'.$subitem->getLink().'</div>';
					echo '<div class="desc">'.$subitem->getDescription().'</div>';
					//echo $subitem->
					echo '<div class="space"></div>';
					echo "</li>";
					//	po stylach	if($module["module_style"] == 1)
					//  if($options["show_icon"])	// po opcjach
				}
				echo '</ul>';
				break;
			case 1: // ikona po prawej, tytu�, zajawka
				echo '<ul class="subitems_1">';

				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id["item_id"]);
					echo "<li>";
					echo 'aa'.$subitem->getUrl();
					if ($subitem->getIcon() != '') {
						echo '<div class="sub_icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'"/></div>';
					}
					echo '<div class="title">'.$subitem->getLink().'</div>';
					echo '<div class="desc">'.$subitem->getDescription().'</div>';
					//echo $subitem->
					echo '<div class="space"></div>';
					echo '</li>';
					//	po stylach	if($module["module_style"] == 1)
					//  if($options["show_icon"])	// po opcjach
				}
				echo '</ul>';
				break;
			case 2: // tytu�, zajawka
				break;
				echo '<ul class="subitems_2">';
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					echo '<li>';
					if ($subitem->getIcon() != '') {
						echo '<div class="sub_icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'"/></div>';
					}
					echo '<div class="title">'.$subitem->getLink().'</div>';
					echo '<div class="desc">'.$subitem->getDescription().'</div>';
					//echo $subitem->
					echo '<div class="space"></div>';
					echo '</li>';
					//	po stylach	if($module["module_style"] == 1)
					//  if($options["show_icon"])	// po opcjach
				}
				echo '</ul>';
			case 3: // ikona, tytu� poni�ej
				echo '<ul class="subitems_0">';
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					echo '<li>';
					if ($subitem->getIcon() != '') {
						echo '<div class="sub_icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'"/></div>';
					}
					echo '<div class="space"></div>';
					echo '<div class="title">' . $subitem->getLink() . '</div>';
					echo '<div class="desc">' . $subitem->getDescription() . '</div>';
					//echo $subitem->
					echo '<div class="space"></div>';
					echo '</li>';
					//	po stylach	if($module["module_style"] == 1)
					//  if($options["show_icon"])	// po opcjach
				}
				echo '</ul>';
				break;
		}
	}
	
	/**
	 * Metoda zwraca listę z typami sortowania.
	 * 
	 * @return array
	 */
	public function getSortTypeList() {
		return $this->sortTypeList;
	}
	
	/**
	 * Zwraca typ sortowania.
	 * Gdy typ pusty zwraca typ domyślny.
	 * 
	 * @return integer
	 */
	public function getSortType() {
		// gdy typ zdefiniowany w konstruktorze znajduje się na liscie typów
		if(in_array($this->sortType,array_keys($this->sortTypeList)))
			return $this->sortType;
		// w przeciwnym przypadku zwraca domyślny typ
		else
			return $this->defaultSortType;
	}
}
