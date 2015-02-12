<?php

function www_catalog_get_group_access($ID)
{
    $sql = 'SELECT * FROM `' . DB_PREFIX . 'www_catalog_group_in` WHERE www_catalog_id=' . intval($ID);
    return _db_get($sql);
}

/**
 * Zwraca wpis w katalogu.
 *
 * @return unknown_type
 */
function www_catalog_get($id)
{
	return _db_get_one('SELECT * FROM `'.DB_PREFIX.'www_catalog` WHERE id='.intval($id).' LIMIT 1');
}

/**
 * Zapisuje wpis w katalogu.
 *
 * @return unknown_type
 */
function www_catalog_update($tab)
{

	$t = array(
        'title'=>_db_string($tab['title']),
	    'url'=>_db_string($tab['url']),
        'description'=>_db_string($tab['description']),
        'active'=>_db_int($tab['active']),
	);
	if ($tab['id'] > 0) {
		return _db_update('www_catalog', $t, 'id=' . intval($tab['id']));
	} else {
		return _db_insert('www_catalog', $t);
	}
}

function www_catalog_group_in_update($tab) {
	
	if (is_array($tab['allow_menu_access'])) {
		// usuwa przypisane grupy do wpisu
		_db_query('DELETE from `'.DB_PREFIX.'www_catalog_group_in` WHERE www_catalog_id='.intval($tab['id']));
		foreach ($tab['allow_menu_access'] as $menu_id => $menu_access) {

			$t_access = array(
                            'www_catalog_id' => $tab['id'],
                            'www_catalog_group_id' => $menu_access,
			);
			// dodaje nową grupę
			_db_insert('www_catalog_group_in', $t_access);
		}
	}
}

/**
 * Usuwa wpis w katalogu.
 *
 * @return unknown_type
 */
function www_catalog_delete($id)
{
	return _db_delete('www_catalog','id=' . intval($id), 1);
}

/**
 * Waliduje wpis w katalogu.
 *
 * @return unknown_type
 */
function www_catalog_validate($tab, $T)
{
	$res = array();

	if (trim($tab['url']) == '') {
		$res['url'] = $T['url_error'];
	}
	return $res;
}

function www_catalog_group_fetch_childs($parent = 0)
{
	$R = array();
	$X = _db_get('SELECT * FROM ' . DB_PREFIX . 'www_catalog_group WHERE parent_id=' . intval($parent));
	return $X;
}

function www_catalog_group_list($parent)
{
	$OPTIONS = array();
	$OPTIONS = www_catalog_group_fetch_childs($parent);
	return $OPTIONS;
}

function www_catalog_group_list_all($activeOnly = false)
{
	$q = 'SELECT * FROM ' . DB_PREFIX . 'www_catalog_group';
	if($activeOnly)
	$q .= " WHERE active = 1";

	// sortuje po parent_id i order
	$q .= " ORDER BY parent_id,order ";
	$X = _db_get($q);
	return $X;
}

function www_catalog_group_update($tab)
{
	$UPD = array(
    'id' => _db_int($tab['id']),
    'parent_id' => _db_int($tab['parent_id']),
    'name' => _db_string($tab['name']),
    'active' => _db_int($tab['active'])
	);

	if ($tab['wug_id'] > 0) {
		return _db_update('www_catalog_group', $UPD, 'id=' . intval($tab['id']), 1);
	} else {
		return _db_insert('www_catalog_group', $UPD);
	}
}

/**
 * Zapisuje aktywnosc grupy.
 *
 * @param $id
 * @param $active
 *
 * @return unknown_type
 */
function www_catalog_group_update_active($id,$active)
{
	return _db_query("UPDATE " . DB_PREFIX . "www_catalog_group SET active=". _db_int($active) .
            " WHERE id=" . _db_int($id) .
            "   OR parent_id = " . _db_int($id) );
}


function www_catalog_group_delete($id)
{
	//usuwa wszystkie podgrupy
	_db_delete('www_catalog_group', 'parent_id=' . intval($id), 1);

	return _db_delete('www_catalog_group', 'id=' . intval($id), 1);
}


function www_catalog_group_update_name($name, $id)
{
	_db_query("UPDATE " . DB_PREFIX . "www_catalog_group SET name=" . _db_string($name) . " WHERE id = " . _db_int($id));
}


function www_catalog_group_new($parent)
{
	_db_query("INSERT INTO " . DB_PREFIX . "www_catalog_group (name, parent_id) VALUES ('Nowa grupa', " . _db_int($parent) . ")");
	return mysql_insert_id();
}

