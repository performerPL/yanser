<?php
define('mod_addnotice.class', 1);

require_once 'lib/notice.php';
require_once 'module/Bean.class.php';
require_once 'admin/class/Notice.class.php';

class mod_addnotice extends Mod_Bean
{
    
    /**
     * @var tablica mapująca dla kolumn
     */
    protected $colList = array(
    1 => array("dbCol" => "n_created","colName" => "Data"),
    2 => array("dbCol" => "ngm_name","colName" => "Grupa"),
    3 => array("dbCol" => "n_title","colName" => "Treść"),
    4 => array("dbCol" => "n_status","colName" => "Aktywny"),
    5 => array("dbCol" => "n_expire","colName" => "Data ważnośći"),
    );

	
	/**
	 * Filtruje tablicę z grupami na tablicę z id zaznaczonych grup.
	 * 
	 * @param $array
	 * @return unknown_type
	 */
	private function filterGroups($array) {
		$groups = array();
		
		foreach($array as $row) {
			$groups[] = $row[ng_id];
		}
		
		return $groups;
	}

	public function update($tab)
	{
		return _db_replace('mod_addnotice', array('module_id'=>_db_int($tab['module_id']),'style'=>_db_int($tab['style'])));
	}

	public function remove($id)
	{
		return _db_delete('mod_addnotice', 'module_id='.intval($id), 1);
	}

	function validate($tab)
	{
		$ERRORS = array();
/*		$REQ = array('n_title', 'n_body',);
		foreach ($REQ as $v) {
			if (trim($tab[$v]) == '') {
				$ERRORS[] = $v;
			}
		}

		if (empty($tab['allow_menu_access']) || !is_array($tab['allow_menu_access']) || count($tab['allow_menu_access']) == 0) {
			$ERRORS[] = 'groups';
		}

		if (empty($tab['main_group'])) {
			$ERRORS[] = 'main_groups';
		}*/

	    // sprawdza czy zgadza się kod z obrazka podany przez uzytkownika
        if(!isset($_SESSION["secretImage"]) || strtolower($_SESSION["secretImage"]) != strtolower($_REQUEST["captcha"])) {          
            $ERRORS[] = 'captcha';
        }
		
		if (count($ERRORS) > 0) {
			return $ERRORS;
		}
		return true;
	}

	function get($id)
	{
		return _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_addnotice` WHERE module_id='.intval($id).' LIMIT 1');
	}

	function fetchGroupsC($GROUPS, $parent = 0)
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
	function fetchGroups($GROUPS, $CHECKED = array(), $parent = 0)
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
					if (is_array($CHECKED) && in_array($V['ng_id'], $CHECKED)) {
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
	function fetchMainGroups($returnAll = false)
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

	private function getUser() {
		if(!empty($_SESSION['user_www_id']))
		  return $_SESSION['user_www_id'];
		else
		  // tymczasowo zwraca id dla niezalogowanego uzytkownika
		  return 1;  
	}
	
	function front($module, $Item)
	{
//		if (!empty($_SESSION['user_www_id']) && $_SESSION['user_www_id'] > 0) {			
			$ERRORS = array();
			$Tab = array();
			$Tab['main_groups'] = $this->fetchMainGroups();
			$Tab['menu_list'] = notice_group_list_all(true);
			$registered = false;
			// usunięcie ogłoszenia
			if (!empty($_POST['i_cmd']) && $_POST['i_cmd'] == 'add_notice' && !empty($_POST['submit_del']) && $this->getUser() > 1) {
				$NOTICE = $_POST;
				notice_delete($NOTICE['n_id']);
				echo '<div class="registered">ogłoszenie zostało usunięte</div>';
				$NOTICE = array();
			} 
			// zapis ogłoszenia
			else if (!empty($_POST['i_cmd']) && $_POST['i_cmd'] == 'add_notice') {
				$NOTICE = $_POST;	
			    // zabezpieczenie dla kopiowania ogłoszenia
                $tmpArr = explode("_",$_REQUEST['n_id']);
                // gdy jest to kopia zeruje id ogloszenia
                if($tmpArr[0] == "copy" && $tmpArr[1] > 0) {
                    $_REQUEST['n_id'] = 0;
                }			
				if ($this->validate($NOTICE) === true) {
					$NOTICE['allow_menu_access'] = $_POST['allow_menu_access'];
                    // pobiera dane usera
					$userData = www_user_get($this->getUser());
                    					
					if ($NOTICE['n_id'] > 0) {
						$NOTICE['n_id_old'] = $NOTICE['n_id'];
						
						$dbNotice = notice_get($NOTICE['n_id']);
						// wartość potrzebna przy obliczaniu daty zakończenia
						$NOTICE[n_created] = $dbNotice[n_created];
					} else {
						// tworzy datę utworzenia ogłoszenia
						$NOTICE['n_created'] = date('Y-m-d H:i:s');
					}
					$NOTICE['n_user'] = $this->getUser();
					// pobiera czas trwania ogłoszenia z danych usera lub z globalnych ustawień
                    $NOTICE['duration'] = !empty($userData[wu_notice_duration]) ? $userData[wu_notice_duration] : NOTICE_DURATION ;

					$n_id = notice_update($NOTICE);
					$NOTICE['n_id'] = $n_id;
					notice_update($NOTICE, true);
					//wysylamy maila
					$registered = true;
				} else {
					$ERRORS = $this->validate($NOTICE);
				}
			}
			if ($registered) {
				if ($NOTICE['n_id_old'] > 0) {
					echo '<div class="registered">Ogłoszenie zostało zmienione. W ciągu godziny zmiany ukażą się na portalu.</div>';
					$NOTICE = array();
				} else {
					echo '<div class="registered">Ogłoszenie zostało dodane. W ciągu godziny ukaże się na portalu.</div>';
					$NOTICE = array();
				}
			}

			// dynamicznie pobierany formularz edycji
			if($_REQUEST[i_cmd] == "update_notice" && $this->getUser() > 1) {
				// konczy buforowanie i czyści bufor
				ob_end_clean();
				/*******************************/
				/** SMARTY - edytuj ogłoszenie **/
				/*******************************/
				// zabezpieczenie dla kopiowania ogłoszenia
				$tmpArr = explode("_",$_REQUEST['n_id']);
				if($tmpArr[0] == "copy" && $tmpArr[1] > 0) {
					$_REQUEST['n_id'] = $tmpArr[1];
				}
				$nga = notice_get_group_access($_REQUEST['n_id']);				
				$notice = notice_get($_REQUEST['n_id']);		
				
				$out = array();
				$out[itemId] = $Item->getID();
				$out[n_title] = $notice[n_title];
				$out[n_body] = $notice[n_body];
				$out[n_status] = $notice[n_status];
				$out[n_contact] = $notice[n_contact];
				// szuka pozycji zaznaczonego elementu na liście
				$index = 0;
				foreach($Tab['main_groups'] as $mgroupKey => $mgroup) {
					if($nga[0][ngm_id] == $mgroupKey)
						break;
					$index++;
				}
				
				$out[main_group] = $index;
				$out[groups_tree] = $this->fetchGroups($Tab['menu_list'], $this->filterGroups($nga), 0);
				
				echo json_encode($out);
				// kończy skrypt
				exit;
			}
				
			/*******************************/
			/** SMARTY - dodaj ogłoszenie **/
			/*******************************/
			$out = array();
			$out[itemId] = $Item->getID();
			$out[groups] = $this->fetchGroups($Tab['menu_list'], $NOTICE['allow_menu_access'], 0);
			$out[main_groups] = $Tab['main_groups'];
		    $out[errors] = $ERRORS;
			
			// załącza tablicę z parametrami
			$this->smarty->assign('out',$out);
			// wyświetla listę
			$this->smarty->display("mod_addnotice/notice_add_edit.html");
			
			// liczba wyszukanych ogłoszeń
			$n = count(notice_get_user_notices($this->getUser()));

			// aktualnie wybrana strona
			$offset = $_REQUEST['_notices_offset'];
			if (!intval($offset)) {
				$offset = 0;
			}

			$data = $this->get($module['module_id']);
			// liczba ogłoszeń na stronie
			$limit = $data['per_page'];

			// sortowanie
			if(empty($_REQUEST['_notices_order_by'])) {
				// domyślnie po pierwszej kolumnie malejąco
				$_REQUEST['_notices_order_by'] = 1;
				$_REQUEST['_notices_order_type'] = 1;	
			}
			$orderBy = $this->getOrderBy($_REQUEST['_notices_order_by'],$_REQUEST['_notices_order_type']);

			// wyszukuje ogłoszenia dla danego usera
			// stronnicuje ogłoszenia oraz sortuje wg żadania
			$NOTICES = notice_get_user_notices($this->getUser(), $offset, $limit,$orderBy);

			/*****************************/
			/** SMARTY - lista ogłoszeń **/
			/*****************************/
			$out = array();
			$out[userId] = $this->getUser();
			$out[itemId] = $Item->getID();
			$out[noticesList] = $NOTICES;
			$out[orderType][$_REQUEST['_notices_order_by']] = intval($_REQUEST['_notices_order_type']);
			$out[orderTypeReversed][$_REQUEST['_notices_order_by']] = $this->reverseOrderType($_REQUEST['_notices_order_type']);
			// załącza tablicę z parametrami
			$this->smarty->assign('out',$out);
			// wyświetla listę
			$this->smarty->display("mod_addnotice/notices_list.html");

			if($this->getUser() == 1)
			     return;
			
			?>
<input
	type="hidden" name="old_k_id" value="0" id="old_k_id" />

			<?php
/* TODO paginacja do poprawy!!!
			echo '<div class="paging">';
			if ($n > $limit) {
				if ($offset >= $limit) {
					echo '<span class="previous"><a href="?_notices_order_by='. $_REQUEST['_notices_order_by'] .'&_notices_offset=' . ($offset - $limit) . '&_notices_order_type='. $out[orderType][$_REQUEST['_notices_order_by']] . '#opinie">Poprzednie</a></span>';
				}

				for ($i = 0, $j = 1; $i < $n; $i += $limit, $j++) {

					if ($i == $offset) {
						echo '<span class="current">&nbsp;' . $j . '&nbsp;</span>';
					} else {
						echo '<span><a href="?_notices_order_by='. $_REQUEST['_notices_order_by'] .'&_notices_offset=' . $i . '&_notices_order_type='. $out[orderType][$_REQUEST['_notices_order_by']] . '#opinie">&nbsp;' . $j . '&nbsp;</a></span>';
					}
				}
			}
			if ($offset + $limit < $n) {
				echo '<span class="next"><a href="?_notices_order_by='. $_REQUEST['_notices_order_by'] .'&_notices_offset=' . ($offset + $limit) . '&_notices_order_type='. $out[orderType][$_REQUEST['_notices_order_by']] . '#opinie">Następne</a></span>';
            }
            echo '</div>';
            echo '<div class="space"></div>';
*/
//		}
	}
}
