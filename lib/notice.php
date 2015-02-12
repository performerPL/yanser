<?php

function notice_get_group_access($ID)
{
	$sql = 'SELECT ng_id, n_id, ngm_id FROM `' . DB_PREFIX . 'notice_group_in` WHERE n_id=' . intval($ID);
	return _db_get($sql);
}

function notice_update($tab, $onlyPerm = false)
{
	global $GL_ACCESS_LVL;
	if (empty($tab['main_group'])) {
		$tab['main_group'] = 0;
	}
	if (!$onlyPerm) {
		$t = array(
    'n_title'=>_db_string($tab['n_title']),
    'n_body'=>_db_string($tab['n_body']),
    'n_user' => _db_int($tab['n_user']),
    'n_created' => _db_string($tab['n_created']),
    'n_status' => _db_int($tab['n_status']),
    'n_priority' => _db_dec($tab['n_priority']),
	'n_contact' => _db_string($tab['n_contact']), 
		);
		// dopisuje date koncowa
		if(!empty($tab[n_expire]) && $tab[n_expire] != '0000-00-00') {
			$t[n_expire] = _db_string($tab['n_expire']);
		}
		// oblicza date koncowa
		else if(intval($tab[duration]) > 0) {
			$t[n_expire] = _db_string(Notice :: createExpireDate($tab[duration],$tab['n_created']));
		}
	}

	if (intval($tab['n_id']) == 0) {
		unset($t['n_status']);
	}

	// gdy istnieje już id ogloszenia
	if ($tab['n_id'] > 0) {
		// usuwa zmienna z data utworzenia
		unset($t['n_created']);

		// zapisuje tylko grupy
		if ($onlyPerm) {
			$menu_check = array();
			// pobiera zapisane grupy
			$user_menu_access = notice_get_group_access($tab['n_id']);

			foreach ($user_menu_access as $key => $access) {
				//				// jeśli grupa nie istnieje w przesłanej tablicy usuwa j
				//				if (!array_key_exists($access['ng_id'], $tab['allow_menu_access'])) {
				_db_query('DELETE from `'.DB_PREFIX.'notice_group_in` WHERE ng_id='.intval($access['ng_id']).' and n_id='.intval($tab['n_id']));
				//				}
				//				// jeśli istnieje oznacza ją w tabeli menu_check
				//				else {
				//					$menu_check[$access['ng_id']] = 1;
				//				}
			}

			if (is_array($tab['allow_menu_access'])) {
				foreach ($tab['allow_menu_access'] as $menu_id => $menu_access) {
					//					// gdy grupa jeszcze nie dodana
					//					if (!array_key_exists($menu_id, $menu_check)) {
					$t_access = array(
              				'n_id' => $tab['n_id'],
              				'ng_id' => $menu_access,
              				'ngm_id' => $tab['main_group']
					);
					// dodaje nową grupę
					_db_insert('notice_group_in', $t_access);
					//					}
				}
			}
		}
		// uaktualnia ogłoszenie
		else {
			_db_update('notice', $t, 'n_id=' . intval($tab['n_id']));
			return intval($tab['n_id']);
		}
	}
	// dodaje ogłoszenie
	else {
		$WU_ID = _db_insert('notice', $t);
		return $WU_ID;
	}
}

function notice_group_fetch_childs($parent = 0)
{
	$R = array();
	$X = _db_get('SELECT * FROM ' . DB_PREFIX . 'notice_group WHERE ng_parent_id=' . intval($parent));
	return $X;
}

function notice_group_list($parent)
{
	$OPTIONS = array();
	$OPTIONS = notice_group_fetch_childs($parent);
	return $OPTIONS;
}

function notice_main_group_fetch_childs()
{
	$R = array();
	$X = _db_get('SELECT * FROM ' . DB_PREFIX . 'notice_group_main');
	return $X;
}

function notice_main_group_list()
{
	$OPTIONS = array();
	$OPTIONS = notice_main_group_fetch_childs();
	return $OPTIONS;
}

function notice_group_list_all($activeOnly = false)
{
	$q = 'SELECT * FROM ' . DB_PREFIX . 'notice_group';
	if($activeOnly)
	$q .= " WHERE ng_active = 1";

	// sortuje po parent_id i order
	$q .= " ORDER BY ng_parent_id,ng_order ";
	$X = _db_get($q);
	return $X;
}

function notice_group_update($tab)
{
	$UPD = array(
    'ng_id' => _db_int($tab['ng_id']),
    'ng_parent_id' => _db_int($tab['ng_parent_id']),
    'ng_name' => _db_string($tab['ng_name']),
    'ng_active' => _db_int($tab['ng_active'])
	);

	if ($tab['wug_id'] > 0) {
		return _db_update('notice_group', $UPD, 'ng_id=' . intval($tab['ng_id']), 1);
	} else {
		return _db_insert('notice_group', $UPD);
	}
}

function notice_group_delete($id)
{
	//usuwa wszystkie podgrupy
	_db_delete('notice_group', 'ng_parent_id=' . intval($id), 1);
	
	return _db_delete('notice_group', 'ng_id=' . intval($id), 1);
}

function notice_main_group_delete($id)
{
	//_db_delete('user_access', 'user_id=' . intval($id));
	return _db_delete('notice_group_main', 'ngm_id=' . intval($id), 1);
}

function notice_group_update_name($name, $id)
{
	_db_query("UPDATE " . DB_PREFIX . "notice_group SET ng_name=" . _db_string($name) . " WHERE ng_id = " . _db_int($id));
}

function notice_main_group_update_name($name, $id)
{
	_db_query("UPDATE " . DB_PREFIX . "notice_group_main SET ngm_name=" . _db_string($name) . " WHERE ngm_id = " . _db_int($id));
}

function notice_group_new($parent)
{
	_db_query("INSERT INTO " . DB_PREFIX . "notice_group (ng_name, ng_parent_id) VALUES ('Nowa grupa', " . _db_int($parent) . ")");
	return mysql_insert_id();
}

function notice_main_group_new()
{
	_db_query("INSERT INTO " . DB_PREFIX . "notice_group_main (ngm_name) VALUES ('Nowa grupa')");
	return mysql_insert_id();
}

function notice_get_fetch_p($A, $parent, $first = false)
{
	$R = _db_get_one("SELECT ng_name, ng_parent_id, ng_id FROM " . DB_PREFIX . "notice_group WHERE ng_id=" . _db_int($parent));
	if ($R !== false) {
		if (!$first) {
			$A[] = $R;
		}
		if ($R['ng_parent_id'] > 0 && $R['ng_parent_id'] != 0 && $R['ng_parent_id'] != $parent) {
			notice_get_fetch_p($A, $R['ng_parent_id']);
		}
	}
	return $A;
}

function notice_get_pathway($parent)
{
	return notice_get_fetch_p(array(), $parent, true);
}

function notice_get_group_notices($group)
{
	$sql = 'SELECT * FROM ' . DB_PREFIX . 'notice JOIN ' . DB_PREFIX . 'notice_group_in ON (' . DB_PREFIX . 'notice.n_id=' . DB_PREFIX . 'notice_group_in.n_id) WHERE ng_id=' . _db_int($group) . ' ORDER BY n_created, n_status';
	$RES = _db_get($sql);
	return $RES;
}


function notice_get_user_notices($user, $offset = false, $limit = false, $orderBy = null,$view = "*")
{
	// domyślnie sortuje po dacie stworzenia i statusie
	if(empty($orderBy)) {
		$orderBy = "n_created , n_status";
	}

	// pobiera listę ogłoszen dla danego usera
	$sql =
			' SELECT ' . $view . ' FROM ' . DB_PREFIX . 'notice n '.
			'  LEFT JOIN '. DB_PREFIX . 'notice_group_in ngi ON (n.n_id=ngi.n_id) '.
			'  LEFT JOIN '. DB_PREFIX . 'notice_group_main ngm ON (ngi.ngm_id=ngm.ngm_id) '.
  			' WHERE n_user=' . _db_int($user) .
			' GROUP BY ngi.n_id '. 
  			' ORDER BY ' . $orderBy;

	if ($offset !== false) {
		$sql .= ' LIMIT ' . $offset . ', ' . $limit;
	}
	
	$RES = _db_get($sql,'',null,false);

	return $RES;
}

function notice_main_update_active($id,$active)
{
	return _db_query("UPDATE " . DB_PREFIX . "notice_group_main SET ngm_active=0". _db_int($active) .
			" WHERE ngm_id=" . _db_int($id) );
}

/**
 * Zapisuje aktywnosc grupy.
 *
 * @param $id
 * @param $active
 *
 * @return unknown_type
 */
function notice_update_active($id,$active)
{
	return _db_query("UPDATE " . DB_PREFIX . "notice_group SET ng_active=". _db_int($active) .
			" WHERE ng_id=" . _db_int($id) .
			"	OR ng_parent_id = " . _db_int($id) );
}

/**
 * Uaktualnia statusy przeterminowane dla wszystkich ogłoszeń.
 *
 * @return unknown_type
 */
function notice_update_status($saveLog = false)
{
	if($saveLog) {
		$I = array(
      'wuh_who' => _db_string($_SESSION['cms_logged_user']['user_name']),
      'wuh_date' => _db_string(date('Y-m-d H:i:s')),
      'wuh_what' => _db_string('Update statusów ogłoszeń')
		);
		_db_insert('www_user_history', $I);

	}
	return _db_query("UPDATE " . DB_PREFIX . "notice SET n_status = 2 WHERE n_status = 1 AND n_expire < DATE_FORMAT(NOW(),'%Y-%m-%d')" );
}

function notice_delete($id)
{
	_db_query("DELETE FROM " . DB_PREFIX . "notice_group_in WHERE n_id=" . _db_int($id));
	return _db_query("DELETE FROM " . DB_PREFIX . "notice WHERE n_id=" . _db_int($id));
}

function notice_get($id)
{
	$RES = _db_get_one('SELECT * FROM ' . DB_PREFIX . "notice WHERE n_id=" . _db_int($id));
	return $RES;
}

function notice_get_user_count($user)
{
	$RES = _db_get_one('SELECT COUNT(*) AS licznik FROM ' . DB_PREFIX . "notice WHERE n_user=" . _db_int($user));
	return (int) $RES['licznik'];
}

