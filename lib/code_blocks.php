<?php
if (!defined('_APP')) {
	exit;
}
if (defined('_LIB_MENU.PHP')) {
	return;
}
define('_LIB_MENU.PHP', 1);

/**
 * Zwraca listę bloków kodu.
 *
 * @return unknown_type
 */
function code_block_list($onlyActive = false)
{
    $query = 'SELECT * FROM `' . DB_PREFIX . 'code_blocks`';

    if($onlyActive)
        $query .= " WHERE active = 1 ";
        
    $query .= " ORDER BY name ";
    
	return _db_get($query, 'id');
}

/**
 * Zwraca blok kodu.
 *
 * @return unknown_type
 */
function code_block_get($id)
{
	return _db_get_one('SELECT * FROM `'.DB_PREFIX.'code_blocks` WHERE id='.intval($id).' LIMIT 1');
}

/**
 * Zapisuje blok kodu.
 *
 * @return unknown_type
 */
function code_block_update($tab)
{

	$t = array(
		'name'=>_db_string($tab['name']),
		'description'=>_db_string($tab['description']),
        'code'=>_db_string($tab['code']),
        'active'=>_db_int($tab['active']),
	);
	if ($tab['code_block_id'] > 0) {
		return _db_update('code_blocks', $t, 'id=' . intval($tab['code_block_id']));
	} else {
		return _db_insert('code_blocks', $t);
	}
}

/**
 * Usuwa blok kodu.
 *
 * @return unknown_type
 */
function code_block_delete($id)
{
	return _db_delete('code_blocks','id=' . intval($id), 1);
}

/**
 * Waliduje blok kodu.
 *
 * @return unknown_type
 */
function code_block_validate($tab, $T)
{
	$res = array();

	if (trim($tab['name']) == '') {
		$res['name'] = $T['name_error'];
	}
	return $res;
}
