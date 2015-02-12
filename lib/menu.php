<?php
if (!defined('_APP')) {
  exit;
}
if (defined('_LIB_MENU.PHP')) {
  return;
}
define('_LIB_MENU.PHP', 1);

function menu_list()
{
  return _db_get('SELECT * FROM `' . DB_PREFIX . 'menu` ORDER BY menu_name','menu_id'); //dodać zarz±dzanie orderami
}

function menu_list_access()
{
  return _db_get('SELECT `' . DB_PREFIX . 'menu`.*  FROM `' . DB_PREFIX . 'menu`,`' . DB_PREFIX.'user_menu_access` where `' . DB_PREFIX . 'user_menu_access`.user_id='.intval($_SESSION['cms_logged_user']['user_id']).' and `'.DB_PREFIX.'user_menu_access`.menu_id = `'.DB_PREFIX.'menu`.menu_id ORDER BY menu_name','menu_id'); //dodać zarz±dzanie orderami
}

function menu_get($id)
{
  $R = array();
  $R = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'menu` WHERE menu_id=' . intval($id) . ' LIMIT 1');
  $R['addons'] = array();
  $R['addons'] = _db_get('SELECT * FROM `' . DB_PREFIX . 'menu_addons` WHERE menu_id=' . intval($id));
  return $R;
}

function menu_update($tab)
{
  $t = array(
		'menu_name' => _db_string($tab['menu_name']),
		'menu_code' => _db_string($tab['menu_code']),
		'lang_id' => _db_string($tab['lang_id']),
		'show_in_map' => _db_bool($tab['show_in_map']),
  );
  $idt = 0;

  if ($tab['menu_id'] > 0) {
    $return = _db_update('menu', $t, 'menu_id=' . intval($tab['menu_id']));
    $idt = $tab['menu_id'];
  } else {
    $return = _db_insert('menu', $t);
    $idt = $return;
  }

  _db_delete('menu_addons', 'menu_id=' . intval($idt));

  foreach ($tab['menu_addons_name'] as $k => $V) {
    if (trim($V) == '') {
      continue;
    }
    $I = array(
    'menu_id' => (int) $idt,
    'name' => _db_string($V),
    'value' => _db_string($tab['menu_addons'][$k])
    );
    _db_insert('menu_addons', $I);
  }

  return $return;
}

function menu_delete($id)
{
  // menu można usunąć tylko jak jest puste - nie ma itemów...
  $x = _db_get_one('SELECT * FROM `'.DB_PREFIX.'item` WHERE menu_id='.intval($id).' LIMIT 1');
  if ($x['item_id'] > 0) {
    return false;
  } else {
    return _db_delete('menu', 'menu_id=' . intval($id), 1);
  }
}

function menu_validate($tab, $T)
{
  $res = array();

  if (trim($tab['menu_name']) == '') {
    $res['menu_name'] = $T['menu_name_error'];
  }
  if (!preg_match(ADMIN_CODE_REGEX, $tab['menu_code'])) {
    $res['menu_code'] = $T['menu_code_error'];
  }
  return $res;
}
