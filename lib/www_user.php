<?php

function www_user_group_get($id)
{
    return _db_get_one('SELECT * FROM `'.DB_PREFIX.'www_user_group` WHERE wug_id='.intval($id));
}

function www_user_group_fetch_childs($parent = 0,$idIndex = false)
{
    $R = array();
    if($idIndex) {
        $X = _db_get('SELECT * FROM ' . DB_PREFIX . 'www_user_group WHERE wug_parent_id=' . intval($parent),"wug_id");
    }
    else {
        $X = _db_get('SELECT * FROM ' . DB_PREFIX . 'www_user_group WHERE wug_parent_id=' . intval($parent));
    }

    return $X;
}

function www_user_group_list($parent = 0, $idIndex = false)
{
    $OPTIONS = array();
    $OPTIONS = www_user_group_fetch_childs($parent = 0,$idIndex);
    return $OPTIONS;
}

function www_user_group_list_all($activeOnly = false)
{
    $q = 'SELECT * FROM ' . DB_PREFIX . 'www_user_group';
    if($activeOnly)
    $q .= " WHERE wug_active = 1";

    // sortuje po parent_id i order
    $q .= " ORDER BY wug_parent_id,wug_order ";
    $X = _db_get($q);
    return $X;
}

function www_user_group_update($tab)
{
    $UPD = array(
    'wug_id' => _db_int($tab['wug_id']),
    'wug_parent_id' => _db_int($tab['wug_parent_id']),
    'wug_name' => _db_string($tab['wug_name']),
    'wug_active' => _db_int($tab['wug_active'])
    );

    if ($tab['wug_id'] > 0) {
        return _db_update('www_user_group', $UPD, 'wug_id=' . intval($tab['wug_id']), 1);
    } else {
        return _db_insert('www_user_group', $UPD);
    }
}

function www_user_group_delete($id)
{
    //usuwa wszystkie podgrupy
    _db_delete('www_user_group', 'wug_parent_id=' . intval($id), 1);

    return _db_delete('www_user_group', 'wug_id=' . intval($id), 1);
}


/**
 * Zapisuje aktywnosc grupy.
 *
 * @param $id Id grupy.
 * @param $active Znacznik aktywności grupy.
 *
 * @return unknown_type
 */
function www_user_update_active($id,$active)
{
    return _db_query("UPDATE " . DB_PREFIX . "www_user_group  SET wug_active=". _db_int($active) .
            " WHERE wug_id=" . _db_int($id) .
            "   OR wug_parent_id = " . _db_int($id) );
}

function www_user_group_update_name($name, $id)
{
    _db_query("UPDATE " . DB_PREFIX . "www_user_group SET wug_name=" . _db_string($name) . " WHERE wug_id = " . _db_int($id));
}

function www_user_group_new($parent)
{
    _db_query("INSERT INTO " . DB_PREFIX . "www_user_group (wug_name, wug_parent_id) VALUES ('Nowa grupa', " . _db_int($parent) . ")");
    return mysql_insert_id();
}

function www_user_get_fetch_p($A, $parent, $first = false)
{
    $R = _db_get_one("SELECT wug_name, wug_parent_id, wug_id FROM " . DB_PREFIX . "www_user_group WHERE wug_id=" . _db_int($parent));
    if ($R !== false) {
        if (!$first) {
            $A[] = $R;
        }
        if ($R['wug_parent_id'] > 0 && $R['wug_parent_id'] != 0 && $R['wug_parent_id'] != $parent) {
            www_user_get_fetch_p($A, $R['wug_parent_id']);
        }
    }
    return $A;
}

function www_user_get_pathway($parent)
{
    return www_user_get_fetch_p(array(), $parent, true);
}

function www_user_get($id)
{
    $R = _db_get_one('SELECT * FROM `'.DB_PREFIX.'www_user` WHERE wu_id='.intval($id));
    if (is_array($R) && $R['wu_encrypted'] == 1) {
        $NOT_ENCRYPTED = array('wu_id', 'wu_key', 'wu_encrypted', 'wu_active', 'wu_created', 'wu_modified', 'wu_login', 'wu_password', 'wu_level', 'wu_ip');

        foreach ($R as $k => $V) {
            if (in_array($k, $NOT_ENCRYPTED)) {
                continue;
            }
            $R[$k] = _db_decrypt($V);
        }
    }
    if (USER_SQL_ENCRYPTED == 1) {
        $I = array(
      'wuh_login' => _db_string($R['wu_login']),
      'wuh_who' => _db_string($_SESSION['cms_logged_user']['user_name']),
      'wuh_date' => _db_string(date('Y-m-d H:i:s')),
      'wuh_what' => _db_string('Odczyt danych')
        );
        _db_insert('www_user_history', $I);
    }
    //jeszcze insert odnosnie odczytu jesli mamy dane szyfrowane
    return $R;
}

function www_user_get_by_wu_key($key)
{
    $R = _db_get_one('SELECT wu_id FROM `'.DB_PREFIX.'www_user` WHERE wu_key=\''.$key.'\'');
    return www_user_get($R['wu_id']);
}

/**
 * Sprawdza czy dany mail juz instnieje dla uzytkownika - newsletter
 *
 * @param $email
 * @return unknown_type
 */
function www_user_newsletter_exist($email) {
    $query  = 'SELECT wu_id FROM ' . DB_PREFIX . 'www_user WHERE wu_email=' . _db_string(_db_encrypt($email)) .' AND wu_newsletter=1';
    $XX = _db_get_one($query);

    if(!empty($XX)) {
        return true;
    }
    else {
        return false;
    }
}

/**
 * Usuwa dane usera www i forum.
 *
 * @param $id
 * @return unknown_type
 */
function www_user_delete($id)
{
    return _db_delete('www_user', 'wu_id=' . intval($id), 1);
}

function www_user_verify($key)
{
    $RES = _db_get_one("SELECT wu_login FROM " . DB_PREFIX . "www_user WHERE wu_key=" . _db_string($key));
    _db_query("UPDATE " . DB_PREFIX . "www_user SET wu_active=1, wu_key=NULL WHERE wu_key=" . _db_string($key));
}


function www_user_update($tab, $onlyPerm = false)
{
    global $GL_ACCESS_LVL;
    if (!$onlyPerm) {
        $t = array(
    'wu_login'=>_db_string($tab['wu_login']),
    'wu_email'=>_db_string($tab['wu_email'])
        );
        if ($tab['wu_id'] > 0) {
            unset($t['wu_login']);
        }

        if (USER_SQL_ENCRYPTED == 1) {
            $NOT_ENCRYPTED = array('wu_id', 'wu_key', 'wu_encrypted', 'wu_active', 'wu_created', 'wu_modified', 'wu_login', 'wu_password', 'wu_level', 'wu_ip', 'wu_expire');
            foreach ($t as $k => $v) {
                if (in_array($k, $NOT_ENCRYPTED)) {
                    continue;
                }
                $t[$k] = _db_string(_db_encrypt($tab[$k]));
            }
            $t['wu_encrypted'] = 1;
        }

        if ($tab['wu_password'] != '') {
            $t['wu_password'] = _db_pass($tab['wu_password']);
        }
    } else {
        $t = array(
        'wu_active'=> _db_bool(intval($tab['wu_active'])),
        );
    }

    if ($tab['wu_id'] > 0) {
        if ($onlyPerm) {
            $menu_check = array();
            $user_menu_access = www_user_get_group_access($tab['wu_id']);
            foreach ($user_menu_access as $key => $access) {
                if (!array_key_exists($access['wug_id'], $tab['allow_menu_access'])) {
                    _db_query('DELETE from `'.DB_PREFIX.'www_user_group_in` WHERE wug_id='.intval($access['wug_id']).' and wu_id='.intval($tab['wu_id']));
                }
                $menu_check[$access['wug_id']] = 1;
            }

            if (is_array($tab['allow_menu_access'])) {
                foreach ($tab['allow_menu_access'] as $menu_id => $menu_access) {
                    if (!array_key_exists($menu_id, $menu_check)) {
                        $t_access= array(
              'wu_id' => $tab['wu_id'],
              'wug_id' => $menu_id,
                        );
                        _db_insert('www_user_group_in', $t_access);
                    }
                }
            }
        }
        //jeszcze insert ze byla modyfikacja i encrypting danych jesli trza
        $t['wu_modified'] = _db_string(date('Y-m-d H:i:s'));
        // dodanie klucza gdy zdefiniowany
        if (!empty($tab['wu_key'])) {
            $t['wu_key'] = _db_string($tab['wu_key']);
        }

        if (USER_SQL_ENCRYPTED == 1) {
            $I = array(
      'wuh_login' => _db_string($R['wu_login']),
      'wuh_who' => _db_string($_SESSION['cms_logged_user']['user_name']),
      'wuh_date' => _db_string(date('Y-m-d H:i:s')),
      'wuh_what' => _db_string('Modyfikacja danych')
            );
            _db_insert('www_user_history', $I);
        }
        unset($t['wu_active']);
        return _db_update('www_user', $t, 'wu_id=' . intval($tab['wu_id']));
    } else {
        $t['wu_created'] = _db_string(date('Y-m-d H:i:s')); //dodaj czas utworzenia
        if (!empty($tab['wu_ip'])) {
            $t['wu_ip'] = _db_string($tab['wu_ip']);
        }
        if (!empty($tab['wu_key'])) {
            $t['wu_key'] = _db_string($tab['wu_key']);
        }
        if (USER_SQL_ENCRYPTED == 1) {
            $I = array(
      'wuh_login' => _db_string($R['wu_login']),
      'wuh_who' => _db_string($_SESSION['cms_logged_user']['user_name']),
      'wuh_date' => _db_string(date('Y-m-d H:i:s')),
      'wuh_what' => _db_string('Nowy rekord')
            );
            _db_insert('www_user_history', $I);
        }

        $WU_ID = _db_insert('www_user', $t);
        return $WU_ID;
    }
}

function www_user_validate($tab, $T)
{
    $res = array();

    if (!_is_email($tab['wu_email'])) {
        $res['wu_email'] = $T['email_error'];
    }
    if (trim($tab['wu_login']) == '') {
        $res['wu_login'] = $T['login_error'];
    } else {
        $x = _db_get_one("SELECT wu_id FROM " . DB_PREFIX . "www_user WHERE wu_login=" . _db_string($tab['wu_login']));
        if ($x !== false && intval($tab['wu_id']) == 0) {
            $res['wulogin'] = $T['user_name_exists_error'];
        }
    }

    if (($tab['wu_password'] != '' && $tab['wu_password'] != $tab['wu_password2']) || (intval($tab['wu_id'])==0 && $tab['wu_password']=='')) {
        $res['wu_password'] = $T['passwd_error'];
    }
    return $res;
}

function www_user_list($orderBy = "wu_login",$active_only = false)
{
    $sql = ' SELECT * FROM `'.DB_PREFIX.'www_user`';
    if($active_only) {
       $sql .= ' WHERE wu_active = 1';
    }
    $sql .= ' ORDER BY '.$orderBy;
    return _db_get($sql,'wu_id');
}

function www_user_get_group_access($ID,$index_key = '')
{
    $sql = 'SELECT wug_id, wu_id FROM `' . DB_PREFIX . 'www_user_group_in` WHERE wu_id=' . intval($ID);
    if(!empty($index_key)) {
        return _db_get($sql,$index_key);
    }
    else {
        return _db_get($sql);
    }

}

function www_user_get_history()
{
    return _db_get('SELECT * FROM ' . DB_PREFIX . 'www_user_history');
}

function www_user_login($login, $password)
{
    $RES = _db_get_one('SELECT wu_id  FROM ' . DB_PREFIX . 'www_user WHERE wu_login=' . _db_string($login) . ' AND wu_password=' . _db_string(md5($password)) . ' AND wu_active=1 AND wu_newsletter=0');
    if ($RES === false) {
        return false;
    }
    return $RES['wu_id'];
}

/**
 * Pobiera aktywnych userów którzy należą do jednej z grup.
 *
 * @param $groups_in
 * @return unknown_type
 */
function www_user_groups_in($groups_in)
{
    $groups = '';
    foreach($groups_in as $group) {
        if(empty($groups)) {
            $groups .= " ( ";
        }
        else {
            $groups .= " OR ";
        }
        $groups .= ' wugi.wug_id = '. $group;
    }
    $groups .= ') ';
    // dodaje do warunku
    $where .= ' AND '.$groups;



    $query = 'SELECT wu.wu_id '
    . ' FROM ' . DB_PREFIX . 'www_user_group_in wugi '
    . 'LEFT JOIN ' . DB_PREFIX . 'www_user wu ON (wugi.wu_id = wu.wu_id) '
    . ' WHERE wu.wu_active = 1 '
    . $where . ' '
    . ' GROUP BY wu.wu_id ';

    $list = _db_get($query,'wu_id');

    return $list;
}
