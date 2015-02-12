<?php
if (!defined('_APP')) {
  exit;
}
if (defined('_LIB_CONFIG.PHP')) {
  return;
}
define('_LIB_CONFIG.PHP', 1);

function config_get_by_code($code)
{
  return _db_get_one('SELECT * FROM `'.DB_PREFIX.'config` WHERE config_code=\''._db_sqlspecialchars($code).'\' AND parent_id=0 LIMIT 1');
}

function config_get_full($id)
{
  $res = array();

  $query = 'SELECT * FROM `' . DB_PREFIX . 'config` WHERE config_id=' . intval($id) . ' OR parent_id=' . intval($id) . ' ORDER BY `order`';

  $result= mysql_query($query);
  _debug(mysql_error(), $query);
  if ($result) {
    while ($row = mysql_fetch_assoc($result)) {
      if ($row['parent_id'] > 0) {
        $res['subconfig'][$row['config_id']] = $row;
      } else {
        $row['subconfig'] = array();
        $row['config_regex'] = ($row['is_group'] ? ADMIN_CODE_REGEX : $row['config_regex']);
        $res = $row;
      }
    }
    mysql_free_result($result);
  }
  return $res;
}

function config_list()
{
  return _db_get('SELECT * FROM `'.DB_PREFIX.'config` ORDER BY parent_id, config_name','config_id'); //dodać zarz±dzanie orderami
}

function config_tree()
{
  $res = array();

  $query = 'SELECT * FROM `'.DB_PREFIX.'config` ORDER BY parent_id, config_name';

  $result= mysql_query($query);
  _debug(mysql_error(), $query);
  if($result) {
    while($row = mysql_fetch_assoc($result)) {
      if($row['parent_id']>0) {
        $res[$row['parent_id']]['subconfig'][$row['config_id']] = $row;
      } else {
        $row['subconfig'] = array();
        $row['config_regex'] = ($row['is_group']?ADMIN_CODE_REGEX:$row['config_regex']);
        $res[$row['config_id']] = $row;
      }
    }
    mysql_free_result($result);
  }

  return $res;
}

function config_get($id)
{
  return _db_get_one('SELECT * FROM `'.DB_PREFIX.'config` WHERE config_id='.intval($id).' LIMIT 1');
}

function config_update($tab)
{
  global $GL_ACCESS_LVL;
  $t = array(
		'config_code'=>_db_string($tab['config_code']),
		'parent_id'=>_db_int($tab['parent_id']),
		'config_icon'=>_db_string($tab['config_icon']),
		'config_name'=>_db_string($tab['config_name']),
		'info'=>_db_string($tab['info']),
		'config_regex'=>_db_string($tab['config_regex']),
		'allow_edit'=>_db_bool($tab['allow_edit']),
		'multiple'=>_db_bool($tab['multiple']),
		'is_group'=>_db_bool($tab['is_group']),
  );
  if($tab['config_id']>0) {
    return _db_update('config',$t,'config_id='.intval($tab['config_id']));
  } else {
    return _db_insert('config',$t);
  }
}

function config_delete($id)
{
  $x = _db_get_one('SELECT * FROM `'.DB_PREFIX.'config` WHERE parent_id='.intval($id).' LIMIT 1');
  if($x['config_id']>0)  {
    return false; // nie mozna usuwac configow, które mają podcofigi
  } else {
    _db_delete('config_value','config_id='.intval($id));
    return _db_delete('config','config_id='.intval($id),1);
  }
}

function config_validate($tab, $T)
{
  $res = array();

  if(trim($tab['config_name'])=='') {
    $res['config_name'] = $T['config_name_error'];
  }
  if(!preg_match(ADMIN_CODE_REGEX,$tab['config_code'])) {
    $res['config_code'] = $T['config_code_error'];
  }
  return $res;
}

function config_list_parents($id)
{
  return _db_get('SELECT * FROM `' . DB_PREFIX . 'config` WHERE parent_id=0 AND is_group>0 AND config_id<>'.intval($id).' ORDER BY config_name','config_id');
}
