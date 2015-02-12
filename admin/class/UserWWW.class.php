<?php
require_once '../module/Bean.class.php';


/**
 * Klasa odpowiadająca za działania w panelu admina dotyczące usera www.
 *
 * @author Darek Skrzypczak <kontakt@app4you.pl>
 *
 */
class UserWWW extends Mod_Bean {


	/**
	 * @var tablica mapująca dla kolumn
	 */
	protected $colList = array(
    "default" => "wu_created DESC",
	1 => array("dbCol" => ""),
	2 => array("dbCol" => ""),
	3 => array("dbCol" => ""),
	4 => array("dbCol" => ""),
	5 => array("dbCol" => ""),
	);

	// scieżka względna
	protected $includePath = '../';


	/**
	 * Zwraca listę użytkowników wg podanych kryteriów.
	 *
	 * @param $criteria
	 * @param $limit
	 * @param $offset
	 * @param $orderBy
	 * @return unknown_type
	 */
	function getUserList($criteria,$limit = null,$offset = null,$orderBy = "wu_created DESC")
	{
		$sql =
          ' SELECT wu_id FROM `'.DB_PREFIX.'www_user` '
          . ' WHERE 1 ';
          // dodanie kryteriów
          if(isset($criteria[show])) {
          	// newsletter
          	if($criteria[show] == 1) {
          		$sql .= ' AND wu_newsletter = 1 ';
          	}
          	// pełny
          	else if($criteria[show] == 2) {
          		$sql .= ' AND wu_newsletter = 0 ';
          	}
          }

          $sql .= ' ORDER BY '.$orderBy;

          // dodanie limitu
          if(!empty($limit)) {
          	$sql .= " LIMIT ". $offset . " , ". $limit;
          }

          $returnList = array();
          $list =  _db_get($sql);

          foreach($list as $row) {
          	$returnList[$row["wu_id"]] = www_user_get($row["wu_id"]);
          }

          return $returnList;
	}

	/**
	 * Wyswietla kod html ze stronnicowaniem opinii.
	 * Stronnicowanie dla listy w adminie.
	 *
	 * @param $pagingObj Obiekt od stronnicowania
	 * @param $activity Typ aktywnosci
	 * @return unknown_type
	 */
	public function getPaging($pagingObj,$show) {
		$out = array();
		$out[paging] = $pagingObj;
		// lista limitów wierszy na stronie
		$out[params][limitList] = array(10 => 10,20 => 20,50 => 50,100 => 100,);
		// lista opcji do pokazywania
		$out[params][showList] = array(0 => "Wszystkie",1 => "Newsletter",2 => "Pełne");
		$out[params][show] = $show;

		// załącza tablicę z parametrami
		$this->smarty->assign('out',$out);
		// wyświetla listę
		$this->smarty->display("admin/user_www/paging.html");
	}


	/**
	 * Sprawdza czy istnieje zagnieżdzenie.
	 * 
	 * @param $GROUPS Tablica z grupami
	 * @param $parent Id grupy nadrzędnej
	 * 
	 * @return unknown_type
	 */
	private function fetchGroupsC($GROUPS, $parent = 0)
	{
		foreach ($GROUPS as $k => $V) {
			if ($V['wug_parent_id'] == $parent) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Tworzy listę(drzewko) z grupami.
	 * Drzewko tworzy się rekurencyjnie.
	 *
	 * @param $GROUPS
	 * @param $parent
	 * @return unknown_type
	 */
	public function fetchGroups($GROUPS,$parent = 0)
	{
		$class = '';//($parent == 0 ? 'root' : '');
		$body =  '<ul>';
		foreach ($GROUPS as $k => $V) {
			if ($V['wug_parent_id'] == $parent) {
				$body .=  '<li id="'. $V['wug_id'] .'" name="'. $parent .'" class="'. $class .'">';
				$body .=  '<span name="spanName">'. $V['wug_name'] .'</span>
                <div name="editDiv" style="display:none;">
                <input name="active" type="checkbox" value="1"';
				if($V[wug_active] == 1)
				$body .= ' checked="checked"';
				$body .=  '>
                <input name="editName" type="text" value="'. $V['wug_name'] .'" /><a name="save">Zapisz</a> <a name="hideEdit">Ukryj<a/>
                </div>
                <div name="buttonsDiv">
                <a name="showEdit">Edytuj<a/>
                <a name="add">Dodaj</a>
                <a name="delete">Usun</a></div>';
				// sprawdza czy istnieje zagnieżdzenie
				if ($this->fetchGroupsC($GROUPS, $V['wug_id'])) {
					$body .= $this->fetchGroups($GROUPS,$V['wug_id']);
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

}
?>