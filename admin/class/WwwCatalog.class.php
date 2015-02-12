<?php
$include_file = '../module/Bean.class.php';
if(file_exists($include_file)){
	require_once $include_file;
}


/**
 * Klasa odpowiadająca za działania w panelu admina dotyczące katalogu www.
 *
 * @author Darek Skrzypczak <kontakt@app4you.pl>
 *
 */
class WwwCatalog extends Mod_Bean {

	private $statusList = array(1 => "Aktywne",0 => "Nieaktywne");

	/**
	 * @var tablica mapująca dla kolumn
	 */
	protected $colList = array(
    "default" => "title , active",
	1 => array("dbCol" => ""),
	2 => array("dbCol" => ""),
	3 => array("dbCol" => ""),
	4 => array("dbCol" => ""),
	5 => array("dbCol" => ""),
	);

	// scieżka względna
	protected $includePath = '../';


	/**
	 * Konstruktor klasy CodeBlock
	 *
	 *
	 */
	public function __construct() {
		parent :: __construct();

	}

	/**
	 * Zwraca listę stron w katalogu www wg podanych kryteriów.
	 *
	 * @param $criteria
	 * @param $limit
	 * @param $offset
	 * @param $orderBy
	 * @return unknown_type
	 */
	function getList($criteria = null,$limit = null,$offset = null,$orderBy = "wc.url")
	{
		$sql =
            ' SELECT * FROM ' . DB_PREFIX . 'www_catalog wc LEFT JOIN ' . DB_PREFIX . 'www_catalog_group_in wcgi ON (wc.id=wcgi.www_catalog_id) '.
            ' WHERE 1 ';

	    // dodanie kryteriów
		if(isset($criteria['show'])) {
			// aktywne
			if($criteria['show'] == 1) {
				$sql .=  ' AND wc.active = 1';
			}
			// nieaktywne
			else {
				$sql .=  ' AND wc.active = 0';
			}
		}

		// filtrowanie po grupie
		if(is_array($criteria['filteredGroups'])) {
			$sql .= ' AND (';
			$first = true;
			foreach($criteria['filteredGroups'] as $groupId) {
				if(!$first) {
					$sql .= ' OR ';
				}
				else {
					$first = false;
				}
				$sql .= ' wcgi.www_catalog_group_id = '. $groupId;
			}
			$sql .= ' ) ';
		}

		$sql .= ' GROUP BY wc.id ';
		$sql .= ' ORDER BY '.$orderBy;

		// dodanie limitu
		if(!empty($limit)) {
			$sql .= " LIMIT ". $offset . " , ". $limit;
		}
			
		$list =  _db_get($sql);

		return $list;
	}

	/**
	 * Zwraca listę grup katalogu www wg podanych kryteriów.
	 *
	 * @param $criteria
	 * @param $limit
	 * @param $offset
	 * @param $orderBy
	 * @return unknown_type
	 */
	function getGroupList($criteria = null,$limit = null,$offset = null,$orderBy = "wcg.parent_id,wcg.order")
	{
		$sql =
            ' SELECT * FROM ' . DB_PREFIX . 'www_catalog_group wcg '.
            ' WHERE 1 ';


		$sql .= ' ORDER BY '.$orderBy;

		// dodanie limitu
		if(!empty($limit)) {
			$sql .= " LIMIT ". $offset . " , ". $limit;
		}
			
		$list =  _db_get($sql);

		return $list;
	}

	/**
	 * Wyswietla kod html ze stronnicowaniem opinii.
	 * Stronnicowanie dla listy w adminie.
	 *
	 * @param $pagingObj Obiekt od stronnicowania
	 * @param $criteria Tablica z warunkami dodatkowymi
	 * @return unknown_type
	 */
	public function getPaging($pagingObj,$criteria) {
		$out = array();
		$out[paging] = $pagingObj;
		$out[params] = $criteria;
		// lista limitów wierszy na stronie
		$out[params][limitList] = array(10 => 10,20 => 20,50 => 50,100 => 100,);
		// lista opcji do pokazywania
		$out[params][showList] = $this->statusList;


		// załącza tablicę z parametrami
		$this->smarty->assign('out',$out);
		// wyświetla listę
		$this->smarty->display("admin/www_catalog/paging.html");
	}



	/**
	 * Filtruje tablicę z grupami na tablicę z id zaznaczonych grup.
	 *
	 * @param $array
	 * @return unknown_type
	 */
	public function filterGroups($array) {
		$groups = array();

		foreach($array as $row) {
			$groups[] = $row['www_catalog_group_id'];
		}

		return $groups;
	}

	private function fetchGroupsC($GROUPS, $parent = 0)
	{
		foreach ($GROUPS as $k => $V) {
			if ($V['parent_id'] == $parent) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Tworzy listę(drzewko) z grupami tematycznymi z edycją i usuwaniem.
	 * Drzewko tworzy się rekurencyjnie.
	 *
	 * @param $GROUPS
	 * @param $CHECKED
	 * @param $parent
	 * @return unknown_type
	 */
	public function fetchAdminGroups($GROUPS, $CHECKED = array(), $parent = 0)
	{
		$class = '';//($parent == 0 ? 'root' : '');
		$body =  '<ul>';
		foreach ($GROUPS as $k => $V) {
			if ($V['parent_id'] == $parent) {
				$body .=  '<li id="'. $V['id'] .'" name="'. $parent .'" class="'. $class .'">';
				$body .=  '<span name="spanName"';
				if($V[active] != 1)
				$body .= ' style="color: #ccc;" ';
				$body .= '>'. $V['name'] .'</span>';
                $body .= '<div name="editDiv" style="display:none;">
                <input name="active" type="checkbox" value="1"';
				if($V[active] == 1)
				$body .= ' checked="checked"';
				$body .=  '>
                <input name="editName" type="text" value="'. $V['name'] .'" />&nbsp;&nbsp;<a name="save">Zapisz</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a name="hideEdit">Ukryj<a/>
                </div>
                <div name="buttonsDiv">
                <a name="showEdit">Edytuj<a/>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a name="add">Dodaj</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a name="delete">Usun</a></div>';
				// sprawdza czy istnieje zagnieżdzenie
				if ($this->fetchGroupsC($GROUPS, $V['id'])) {
					$body .= $this->fetchAdminGroups($GROUPS,array(),$V['id']);
				}
				// dodaje pustego <ul>
				else {
					//$body .=  '<ul></ul>';
				}
				$body .=  '</li>';
			}
		}
		$body .=  '</ul>';

		return $body;
	}


	/**
	 * Tworzy listę(drzewko) z grupami tematycznymi.
	 * Drzewko tworzy się rekurencyjnie.
	 *
	 * @param $GROUPS
	 * @param $CHECKED
	 * @param $parent
	 * @return unknown_type
	 */
	function fetchGroups($GROUPS, $CHECKED = array(), $parent = 0)
	{
		$body =  '<ul>';
		foreach ($GROUPS as $k => $V) {
			if ($V['parent_id'] == $parent) {
				$body .=  '<li>';
				$checked = '';
				if (is_array($CHECKED) && in_array($V['id'], $CHECKED)) {
					$checked = ' checked="checked"';
				}
				$body .=  '<span class="folder"><input type="checkbox" name="allow_menu_access[]" value="' . $V['id'] . '"' . $checked . '/> ' . $V['name'] .'</span>';
				if ($this->fetchGroupsC($GROUPS, $V['id'])) {
					$body .= $this->fetchGroups($GROUPS, $CHECKED, $V['id']);
				}
				$body .=  '</li>';
			}
		}
		$body .=  '</ul>';

		return $body;
	}

}
?>