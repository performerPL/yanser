<?php
if (!defined('_APP')) {
  exit;
}
if (defined('_LIB_TEMPLATE.PHP')) {
  return;
}
define('_LIB_TEMPLATE.PHP', 1);

if (file_exists('../lib/menu.php')) {
  require_once '../lib/menu.php';
} else {
  require_once 'lib/menu.php';
}


function template_get_main()
{
  return _db_get_one('SELECT * FROM `'.DB_PREFIX.'template` WHERE template_main>0 LIMIT 1');
}

function template_get_default()
{
  return _db_get_one('SELECT * FROM `'.DB_PREFIX.'template` WHERE template_def>0 LIMIT 1');
}

function template_list_dirs()
{
  $res = array();
  //echo realpath('../'.TEMPLATE_DIR);
  $path = realpath('../'.TEMPLATE_DIR);

  if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
      if ($file != '.' && $file != '..') {
        if (is_dir($path.'/'.$file) && file_exists($path.'/'.$file.'/'.TEMPLATE_FILE_VIEW) && file_exists($path.'/'.$file.'/'.TEMPLATE_FILE_CTRL)) {
          $res[$file] = $file;
        }
      }
    }
  }
  return $res;
}

function template_list()
{
  return _db_get('SELECT * FROM `' . DB_PREFIX . 'template` ORDER BY template_name','template_id'); //dodać zarz±dzanie orderami
}

function template_list_mid($id)
{
  $menu_access = template_get_menu_access2($id);
  $R = array();
  foreach ($menu_access as $V) {
    $X = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'template` WHERE template_id=' . intval($V['template_id']) . ' ORDER BY template_name');
    if ($X == false) {
      continue;
    }
    $R[$X['template_id']] = $X;  
  }
  return $R;
}

function template_get_menu_access($ID)
{
  return _db_get('SELECT menu_id FROM `' . DB_PREFIX . 'template_menu` WHERE template_id=' . intval($ID));
}

function template_get_menu_access2($ID)
{
  return _db_get('SELECT template_id FROM `' . DB_PREFIX . 'template_menu` WHERE menu_id=' . intval($ID));
}

function template_get($id)
{
  $R = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'template` WHERE template_id=' . intval($id) . ' LIMIT 1');
  $R['menu_access'] = template_get_menu_access($id);
  $R['menu_list'] = menu_list();
  return $R;
}

function template_validate($tab, $T)
{
  $res = array();

  if (trim($tab['template_name'])=='') {
    $res['template_name'] = $T['template_name_error'];
  }
  if (trim($tab['template_dir'])=='' || !file_exists(realpath('../'.TEMPLATE_DIR).'/'.$tab['template_dir'].'/'.TEMPLATE_FILE_CTRL)|| !file_exists(realpath('../'.TEMPLATE_DIR).'/'.$tab['template_dir'].'/'.TEMPLATE_FILE_VIEW)) {
    $res['template_dir'] = $T['template_dir_error'];
  }

  return $res;
}

function template_delete($id)
{
  // menu można usunąć tylko jak jest puste - nie ma itemów...
  $x = template_get($id);
  if ($x['template_def']>0 || $x['template_main']>0)  {
    return false;
  } else {
    return _db_delete('template', 'template_id=' . intval($id), 1);
  }
}

function template_update($tab)
{

  $t = array(
		'template_name'=>_db_string($tab['template_name']),
		'template_dir'=>_db_string($tab['template_dir']),
		'info'=>_db_string($tab['info']),
		'active'=>_db_bool($tab['active']),
		'template_def'=>_db_bool($tab['template_def']),
		'template_main'=>_db_bool($tab['template_main']),
  );
  $x = 0;
  if ($tab['template_id']>0) {
    $x = _db_update('template', $t, 'template_id='.intval($tab['template_id']));
    if ($x) {
      $x = $tab['template_id'];
    }
  } else {
    $x = _db_insert('template', $t);
  }
  //var_dump($tab);
  if ($tab['template_def']>0 && $x>0) {
    //echo '!!!';
    _db_update('template',array('template_def'=>_db_bool(false)),'template_id<>'.intval($x),0);
  }
  if ($tab['template_main']>0 && $x>0) {
    //echo '@@@';
    _db_update('template',array('template_main'=>_db_bool(false)),'template_id<>'.intval($x),0);
  }
  
    $user_menu_access = template_get_menu_access($x);
    foreach ($user_menu_access as $key => $access) {
      if (!array_key_exists($access['menu_id'], $tab['mod_allow_menu_access'])) {
        _db_query('DELETE from `'.DB_PREFIX.'template_menu` WHERE menu_id='.intval($access['menu_id']).' and template_id='.intval($x));
      }
      $menu_check[$access['menu_id']] = 1;
    }

    if (is_array($tab['mod_allow_menu_access'])) {
      foreach ($tab['mod_allow_menu_access'] as $menu_id => $menu_access) {
        if (!array_key_exists($menu_id, $menu_check)) {
          $t_access = array(
					'template_id' => $x,
					'menu_id' => $menu_id,
          );
          _db_insert('template_menu', $t_access);
        }
      }
    }
  
  return $x;
}