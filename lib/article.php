<?php
if (!defined('_APP')) {
	exit;
}
if (defined('_LIB_ARTICLE.PHP')) {
	return;
}
define('_LIB_ARTICLE.PHP', 1);

function article_mod_validate($tab, $T)
{

	$res = article_mod_call($tab['module_type'], 'validate', $tab, $T);

	if(!is_array($res))  {
		$res = array();
	}

	if (trim($tab['module_name']) == '') {
		$res['module_name'] = $T['item_mod_name_error'];
	}
	return $res;
}

function article_mod_update($tab)
{
	article_mod_call($tab['module_type'], 'update', $tab);

	$q = array(
		'access_level'=>_db_int($tab['access_level']),
		'module_name'=>_db_string($tab['module_name']),
		'module_style'=>_db_int($tab['module_style']),
		'active'=>_db_bool($tab['module_active']),
		'show_module_title'=>_db_bool($tab['show_module_title']),
	    'vip_code'=>_db_string($tab['vip_code']),
	);
	return _db_update('article_content', $q, 'module_id=' . intval($tab['module_id']));
}

function article_mod_delete($id) {

	$mod = article_mod_get($id);
	$no = article_mod_new_order($mod['article_id']);
	article_mod_reorder($mod['module_order'],$no-1,$mod['article_id']);

	article_mod_call($mod['module_type'],'remove',$id);

	$x = _db_delete('article_content','module_id='.intval($id),1);

	return $x;
}

function article_mod_get($id)
{
	$res = _db_get_one('SELECT * FROM `'.DB_PREFIX.'article_content` WHERE module_id='.intval($id).' LIMIT 1');
	$res['module_active'] = $res['active'];
	unset($res['active']);
	$res2 = article_mod_call($res['module_type'], 'get', $id);

	if (is_array($res2)) {
		return array_merge($res, $res2);
	} else {
		return $res;
	}

}

function article_mod_call($mod_type, $func, $param, $param2='')
{
	global $GL_MOD_TYPE;
	$type = $GL_MOD_TYPE[$mod_type]->script;

	if (file_exists('module/' . $type . '.class.php')) {
		require_once 'module/' . $type . '.class.php';
		if (class_exists($type)) {
			$c = new $type();
			if ($param2 != '') {
				return $c->{$func}($param, $param2);
			} else {
				return $c->{$func}($param);
			}
		}
	}
	return false;
}

function article_mod_list($id)
{
	return _db_get('SELECT * FROM `'.DB_PREFIX.'article_content` WHERE article_id='.intval($id).' ORDER BY module_order','module_id');
}

function article_mod_add($ArticleID, $type, $name)
{
	$q = array(
		'article_id'=>_db_int($ArticleID),
		'module_type'=>_db_int($type),
		'module_name'=>_db_string($name),
		'module_order'=>_db_int(article_mod_new_order($ArticleID)),
	);
	$module_id =  _db_insert('article_content', $q);

	//dodanie wpisu do odpowiedniej tabeli modułu
	article_mod_call($type, 'update', array('module_id' => $module_id));

	// zwraca id modułu
	return $module_id;
}

/**
 * Klonuje moduł.
 * 
 * @param $cloneData
 * @return unknown_type
 */
function article_mod_add_clone($cloneData)
{
    $cloneModuleId = $cloneData['module_id'];
    $moduleType = $cloneData['module_type'];
    unset($cloneData['module_id']);
	$module_id =  _db_insert2('article_content', $cloneData);

    // pobiera dane o module
    $modData = article_mod_call($moduleType, 'get', $cloneModuleId);
    // zamienia id modulu na nowe
    $modData['module_id'] = $module_id;
    
	//dodanie wpisu do odpowiedniej tabeli modułu
    article_mod_call($moduleType, 'update', $modData);

    // zwraca id modułu
    return $module_id;
}

function article_mod_new_order($ArticleID)
{
	return _db_new_order('article_content','module_order','article_id',$ArticleID);
}

function article_mod_get_orders($a_id)
{
	return _db_get('SELECT module_id FROM `'.DB_PREFIX.'article_content` WHERE article_id='.intval($a_id).' ORDER BY module_order');
}

function article_mod_reorder($oo, $no, $a_id)
{
	return _db_reorder('article_content','module_order',$oo,$no,'article_id',$a_id);
}

function article_create()
{
	return _db_insert('article', array());
}

function article_delete($id)
{
	$res = article_mod_list($id);
	foreach ($res as $module) {
		article_mod_delete($module['module_id']);
	}
	return _db_delete('article', 'article_id = ' . _db_int($id));
}

function article_mod_update_order($modOrder, $modId)
{
	return _db_query('UPDATE `'.DB_PREFIX.'article_content` SET module_order='.$modOrder.' WHERE module_id='.$modId.';');
}

function set_article_mod_update_order($modOrder, $modId, $articleId)
{
	return _db_query('UPDATE `'.DB_PREFIX.'article_content` SET module_order='.$modOrder.' WHERE module_id='.$modId.' AND article_id = '.$articleId.';');
}
/**
 * Pobiera dane modułu na podstawie Id artykulu i typu modułu.
 * Zakładamy że pobiera zawsze pierwszy napotkany w artykule, jeżeli jest ich więcej (czasami może się zdarzyć...)
 * 
 * @param unknown_type $article_id
 * @param unknown_type $module_type
 * @return unknown_type
 */
function article_mod_get_by_type($article_id,$module_type)
{
    $res = _db_get_one('SELECT * FROM `'.DB_PREFIX.'article_content` WHERE module_type='.intval($module_type).' AND article_id='.intval($article_id).' LIMIT 1');
    $res['module_active'] = $res['active'];
    unset($res['active']);
    $res2 = article_mod_call($res['module_type'], 'get', $res['module_id']);

    if (is_array($res2)) {
        return array_merge($res, $res2);
    } else {
        return $res;
    }

}