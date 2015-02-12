<?php
if (!defined('_APP')) {
  exit;
}
if (defined('_LIB_USER.PHP')) {
  return;
}
define('_LIB_USER.PHP', 1);

function user_authenticate($login, $passwd)
{
  return _db_get_one('SELECT * FROM `'.DB_PREFIX.'user` WHERE login = ' . _db_string($login) . ' AND passwd = ' . _db_pass($passwd));
}

function get_users_online()
{
  $timeout = time() - 300;
  $q = "DELETE FROM " . DB_PREFIX . "user_session WHERE time < " . $timeout;
  _db_query($q);
  $q = "SELECT u.user_name FROM " . DB_PREFIX . "user_session s, " . DB_PREFIX . "user u WHERE u.session=s.session";
  $U = _db_get($q);
  return $U;
}

function user_delete_session()
{
  $q = "DELETE FROM " . DB_PREFIX . "user_session WHERE session = '" . session_id() . "'";
  _db_query($q);
}

function user_update_time()
{
  $I = array(
    'session' => "'" . session_id() . "'",
    'time' => time(),
  );

  $timeout = time() - 300;

  _db_replace('user_session', $I);
  $q = "DELETE FROM " . DB_PREFIX . "user_session WHERE time < " . $timeout;
  _db_query($q);
  return true;
}

function user_list()
{
  return _db_get('SELECT * FROM `'.DB_PREFIX.'user` ORDER BY user_name','user_id'); //dodać zarz±dzanie orderami
}

function user_get($id)
{
  return _db_get_one('SELECT * FROM `'.DB_PREFIX.'user` WHERE user_id='.intval($id));
}

function user_get_menu_access($id)
{
  return _db_get('SELECT menu_id FROM `' . DB_PREFIX . 'user_menu_access` WHERE user_id='.intval($id));
}

function user_update($tab, $onlyPerm = false)
{
  global $GL_ACCESS_LVL;
  if (!$onlyPerm) {
    $t = array(
		'user_name'=>_db_string($tab['user_name']),
		'email'=>_db_string($tab['email']),
		'login'=>_db_string($tab['login']),
		'phone'=>_db_string($tab['phone']),
		'info'=>_db_string($tab['info']),
    );


    if ($tab['passwd'] != '') {
      $t['passwd'] = _db_pass($tab['passwd']);
    }
  } else {
    $t = array(
		'allow_upload'=>_db_bool(intval($tab['allow_upload'])),
    		'active'=> _db_bool(intval($tab['active'])),
    		'access_level'=> _db_int(isset($GL_ACCESS_LVL[$tab['access_level']]) ? intval($tab['access_level']) : 0),
    );
  }

  if ($tab['user_id'] > 0) {
    if ($onlyPerm) {
      $menu_check = array();
      $user_menu_access = user_get_menu_access($tab['user_id']);
      foreach ($user_menu_access as $key => $access) {
        if (!array_key_exists($access['menu_id'],$tab['allow_menu_access'])) {
          _db_query('DELETE from `'.DB_PREFIX.'user_menu_access` WHERE menu_id='.intval($access['menu_id']).' and user_id='.intval($tab['user_id']));
        }
        $menu_check[$access['menu_id']] = 1;
      }

      if (is_array($tab['allow_menu_access'])) {
        foreach ($tab['allow_menu_access'] as $menu_id => $menu_access) {
          if (!array_key_exists($menu_id, $menu_check)) {
            $t_access= array(
					'user_id' => $tab['user_id'],
					'menu_id' => $menu_id,
            );
            _db_insert('user_menu_access', $t_access);
          }
        }
      }
    }
    return _db_update('user', $t, 'user_id=' . intval($tab['user_id']));
  } else {
    $t['created'] = _db_time('', true); //dodaj czas utworzenia
    return _db_insert('user', $t);
  }
}

function user_logged($login)
{

  _db_query("UPDATE " .DB_PREFIX. "user SET session='' WHERE session='" . session_id() . "'");

  $t = array(
		'last_login' => _db_time('', true),
        'session' => _db_string(session_id())
  );
  return _db_update('user', $t, 'login = ' . _db_string($login));
}

function user_delete($id)
{
  _db_delete('user_access', 'user_id=' . intval($id));
  return _db_delete('user', 'user_id=' . intval($id), 1);
}

function user_validate($tab, $T)
{
  $res = array();

  if (trim($tab['user_name']) == '') {
    $res['user_name'] = $T['user_name_error'];
  }
   
  if (!_is_email($tab['email'])) {
    $res['email'] = $T['email_error'];
  }
  if (trim($tab['login']) == '') {
    $res['login'] = $T['login_error'];
  } else {
    $x = _db_get_one("SELECT user_id FROM " . DB_PREFIX . "user WHERE login=" . _db_string($tab['login']));
    if ($x !== false && intval($tab['user_id']) == 0) {
      $res['login'] = $T['user_name_exists_error'];
    }
  }

  if (($tab['passwd'] != '' && $tab['passwd']!=$tab['passwd2']) || (intval($tab['user_id'])==0 && $tab['passwd']=='')) {
    $res['passwd'] = $T['passwd_error'];
  }
  return $res;
}

function user_add_access()
{
}

function user_del_access()
{
}

function user_get_access()
{
}
