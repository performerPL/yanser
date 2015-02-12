<?php
if (!defined('_APP')) {
  exit;
}
if (defined('_LIB_CONFIG_VALUE.PHP')) {
  return;
}
define('_LIB_CONFIG_VALUE.PHP', 1);

function config_value_create($conf)
{
  //pobiera wartość dla $confa, który nie jest multiplem
  $res = _db_get_one('SELECT * FROM `'.DB_PREFIX.'config_value` WHERE config_id='.intval($conf['config_id']).' AND parent_id=0 LIMIT 1');
  return intval($res['value_id']);
}

function config_value_tree($code='', $admin=false)
{
  $res = array();

  $query = 'SELECT cv.*,c.config_code,c.is_group,c.multiple,cc.config_code as parent_code FROM (`'.DB_PREFIX.'config_value` cv LEFT JOIN `'.DB_PREFIX.'config` c ON cv.config_id=c.config_id) LEFT JOIN `'.DB_PREFIX.'config` cc ON c.parent_id=cc.config_id ';

  if ($code != '') {
    $query .= ' WHERE c.config_code=\''._db_sqlspecialchars($code).'\' OR cc.config_code=\''._db_sqlspecialchars($code).'\'';
  }

  $query .= ' ORDER BY cv.parent_id,c.parent_id';

  $result= mysql_query($query);
  _debug(mysql_error(), $query);
  $vals = array(); //KOD = warto¶ć - dla multiple

  if ($result) {
    while ($row = mysql_fetch_assoc($result)) {
      if ($row['parent_id'] == 0) {
        if (!is_array($res[$row['config_code']])) {
          $res[$row['config_code']] = array();
        }
        if ($row['is_group']) {
          if ($row['multiple']) {
            if ($admin) {
              $vals[$row['value_id']] = $row['value_id'];
              $res[$row['config_code']][$row['value_id']] = array();
              $res[$row['config_code']][$row['value_id']][$row['config_code']] = $row['config_value'];
            } else {
              $vals[$row['value_id']] = $row['config_value'];
              $res[$row['config_code']][$row['config_value']] = array();
            }
          }
        } else {
          if ($row['multiple']) {
            if ($admin) {
              $res[$row['config_code']][$row['value_id']] = $row['config_value'];
            } else {
              $res[$row['config_code']][] = $row['config_value'];
            }
          } else {
            $res[$row['config_code']] = $row['config_value'];
          }
        }
      } else {
        if ($vals[$row['value_parent_id']] != '') {
          $res[$row['parent_code']][$vals[$row['value_parent_id']]][$row['config_code']] = $row['config_value'];
        } else {
          $res[$row['parent_code']][$row['config_code']] = $row['config_value'];
        }
      }
    }
    mysql_free_result($result);
  }
  //var_dump($res);
  return $res;
}

function config_value_get($id, $conf)
{
  $res = array();
  if ($id > 0) {
    if ($conf['is_group'] > 0) {
      $rows = _db_get('SELECT * FROM `'.DB_PREFIX.'config_value` WHERE value_id='.intval($id).' OR value_parent_id='.intval($id),'config_id');
      //mamy coś do pobrania - wszystko co ma parent_value_id==x, value_id=0
      	
      foreach ($conf['subconfig'] as $k => $v) {
        $res[$v['config_code']] = $rows[$v['config_id']]['config_value'];
      }
      if ($conf['multiple'] > 0) {
        $res[$conf['config_code']] = $rows[$conf['config_id']]['config_value'];
      }
    } else {
      $row = _db_get_one('SELECT * FROM `'.DB_PREFIX.'config_value` WHERE value_id='.intval($id).' LIMIT 1');
      $res[$conf['config_code']] = $row['config_value'];
    }
  }
  return $res;
}

function config_value_update($tab, $conf)
{
  if ($conf['is_group'] > 0) {
    $value_id = intval($tab['value_id']);

    $q = array(
			'config_id'=>_db_int($conf['config_id']),
			'parent_id'=>_db_int(0),
			'value_parent_id'=>_db_int(0),
			'config_value'=>_db_string($tab[$conf['config_code']]),
    );
    if ($value_id > 0) {
      _db_update('config_value', $q, 'value_id=' . $value_id);
    } else {
      $value_id = _db_insert('config_value', $q);
    }
    if ($value_id>0) {
      _db_delete('config_value', 'value_parent_id=' . intval($value_id));
    }
    foreach($conf['subconfig'] as $k=>$v) {
      $q = array(
				'config_id'=>_db_int($v['config_id']),
				'parent_id'=>_db_int($conf['config_id']),
				'value_parent_id'=>_db_int($value_id),
				'config_value'=>_db_string($tab[$v['config_code']]),
      );
      _db_insert('config_value', $q);
    }
    return true;
  } else {
    //nie jest grupą -- pojedyncza wartość
    $q = array(
			'config_id'=>_db_int($conf['config_id']),
			'parent_id'=>_db_int(0),
			'value_parent_id'=>_db_int(0),
			'config_value'=>_db_string($tab[$conf['config_code']]),
    );
    if ($tab['value_id'] > 0) {
      return _db_update('config_value', $q, 'value_id=' . intval($tab['value_id']));
    } else {
      return _db_insert('config_value', $q);
    }
  }
}

function config_value_delete($id)
{
  return _db_delete('config_value', 'value_parent_id=' . intval($id) . ' OR value_id=' . intval($id));
}

function config_value_validate($tab, $conf, $T)
{
  $res = array();

  //var_dump($tab);
  if ($conf['is_group'] > 0) {
    foreach ($conf['subconfig'] as $k => $v) {
      if ($v['config_regex'] != '') {
        if (!preg_match($v['config_regex'], $tab[$v['config_code']])) {
          $res[$v['config_code']] = $T['config_value_error'];
        }
      }
    }
    if ($conf['multiple'] > 0) {
      if ($conf['config_regex'] != '') {
        if (!preg_match($conf['config_regex'], $tab[$conf['config_code']])) {
          $res[$conf['config_code']] = $T['config_value_error'];
        } else {
          //sprawdz, czy taki id nie jest już użyty.
          $r = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'config_value` WHERE config_id=' . intval($conf['config_id']) . ' AND config_value=\'' . _db_sqlspecialchars($tab[$conf['config_code']]) . '\' LIMIT 1');
          if (is_array($r) && $r['value_id'] != $tab['value_id']) {
            $res[$conf['config_code']] = $T['config_value_error1'];
          }
        }
      }
    }
  } else {
    if ($conf['config_regex'] != '') {
      if (!preg_match($conf['config_regex'], $tab[$conf['config_code']])) {
        $res[$conf['config_code']] = $T['config_value_error'];
      }
    }
  }
  return $res;
}
