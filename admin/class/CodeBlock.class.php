<?php
$include_file = '../module/Bean.class.php';
if(file_exists($include_file)){
    require_once $include_file;
}


/**
 * Klasa odpowiadająca za działania w panelu admina dotyczące bloków kodu.
 * 
 * @author Darek Skrzypczak <kontakt@app4you.pl>
 *
 */
class CodeBlock extends Mod_Bean {

    /**
     * @var tablica mapująca dla kolumn
     */
    protected $colList = array(
    "default" => "n_created , n_status",
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
     * Zwraca listę blokow kodu wg podanych kryteriów.
     * 
     * @param $criteria
     * @param $limit
     * @param $offset
     * @param $orderBy
     * @return unknown_type
     */
    function getList($criteria = null,$limit = null,$offset = null,$orderBy = "cb.name")
    {
        $sql =  
            ' SELECT * FROM ' . DB_PREFIX . 'code_blocks cb '.
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
        $this->smarty->display("admin/notice/paging.html");
    }
	
    
    public function showEditForm($data) {
    	/*******************************/
    	/** SMARTY - dodaj ogłoszenie **/
    	/*******************************/
    	$groupAccess = notice_get_group_access($_REQUEST['n_id']);
    	$groupList = notice_group_list_all(true);
    	       
    	$out = $data;
    	$out[groups] = $this->fetchGroups($groupList, $this->convertGroupAccess($groupAccess), 0);
    	$out[main_groups] = $this->fetchMainGroups();
    	$out[errors] = $ERRORS;
    	$out[includePath] = $this->includePath;
    	$out[statusList] = $this->statusList;
    	// szuka pozycji zaznaczonego elementu na liście   	
    	foreach($out[main_groups] as $mgroupKey => $mgroup) {
    		if($groupAccess[0][ngm_id] == $mgroupKey) {
    		    $out[main_group] = $mgroupKey;
    			break;
    		}
    	}
        
    	
    	// załącza tablicę z parametrami
    	$this->smarty->assign('out',$out);
    	// wyświetla listę
    	$this->smarty->display("admin/notice/notice_add_edit.html");
    }
    
    
    private function fetchGroupsC($GROUPS, $parent = 0)
    {
        foreach ($GROUPS as $k => $V) {
            if ($V['ng_parent_id'] == $parent) {
                return true;
            }
        }
        return false;
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
    private function fetchGroups($GROUPS, $CHECKED = array(), $parent = 0)
    {
        $body =  '<ul>';
        foreach ($GROUPS as $k => $V) {
            if ($V['ng_parent_id'] == $parent) {
                $body .=  '<li>';
                if ($this->fetchGroupsC($GROUPS, $V['ng_id'])) {
                    $body .=  '<span class="folder">' . $V['ng_name'] . '</span>';
                    $body .= $this->fetchGroups($GROUPS, $CHECKED, $V['ng_id']);
                } else {
                    $checked = '';
                    if (in_array($V['ng_id'], $CHECKED)) {
                        $checked = ' checked="checked"';
                    }
                    $body .=  '<span class="folder"><input type="checkbox" name="allow_menu_access[]" value="' . $V['ng_id'] . '"' . $checked . '/> ' . $V['ng_name'] .'</span>';
                }
                $body .=  '</li>';
            }
        }
        $body .=  '</ul>';
        
        return $body;
    }

    /**
     * Pobiera listę grup głównych.
     * 
     * @param $returnAll Znacznik czy ma zwrócić wszystko czy tablize klucz => wartosc
     * @return unknown_type
     */
    private function fetchMainGroups($returnAll = false)
    {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'notice_group_main WHERE ngm_active=1';
        $RES = _db_get($sql);

        if(!$returnAll) {
            $parsedArray = array();
            // przerabia zwracana tablice na postać klucz => nazwa grupy
            foreach($RES as $key => $row) {
                $parsedArray[$row[ngm_id]] = $row[ngm_name];
            }
            // zwraca przerobiona tablice
            return $parsedArray;    
        }
        else
            // zwraca wszystkie dane z wiersza
            return $RES;
    }
    
    
    /**
     * Konwertuje tablicę z grupami na tablicę z id zaznaczonych grup.
     * 
     * @param $array
     * @return unknown_type
     */
    private function convertGroupAccess($array) {
        $groups = array();
        
        foreach($array as $row) {
            $groups[] = $row[ng_id];
        }
        
        return $groups;
    }
    
    /**
     * Oblicza datę ważności ogłoszenia.
     * 
     * @param $duration Czas ważności ogłoszenia w dniach
     * @param $createdDate Data utworzenia ogłoszenia.
     * 
     * @return string Data w formacie Y-m-d
     */    
    public static function createExpireDate($duration,$createdDate) {
    	return date("Y-m-d",strtotime("+ ".$duration." day",strtotime($createdDate)));
    }
}
?>