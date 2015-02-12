<?php
if (!defined('_APP')) {
    exit;
}
if (defined('_LIB_PROMOTION.PHP')) {
    return;
}
define('_LIB_PROMOTION.PHP', 1);

function promotion_list_item($name = '', $limit=0)
{
//    $query = 'SELECT i.* '
//    . 'FROM ' . DB_PREFIX . 'item i '
//    . 'LEFT JOIN cms_article a ON (i.article_id = a.article_id) '
//    . 'JOIN ' . DB_PREFIX . 'item_promotion ip ON (ip.item_id = i.item_id) '
//    . 'JOIN ' . DB_PREFIX . 'promotion p ON (p.promotion_id = ip.promotion_id) '
//    . 'WHERE promotion_code = ' . _db_string($name) . ' '
//    . 'AND i.active > 0 AND i.show_start <= NOW() AND (i.show_end >= NOW() OR i.show_endless > 0) '
//    . 'AND (p.article_group > 0 OR ('
//    . 'ip.date_start <= NOW() AND (ip.date_end >= NOW() OR p.allow_endless AND ip.date_endless OR ip.date_end = 0)'
//    . ')) ORDER BY a.order, i.show_start DESC ';

     $query = 'SELECT i.* FROM (`'.DB_PREFIX.'item` i LEFT JOIN `'.DB_PREFIX.'item_promotion` ip ON ip.item_id=i.item_id) LEFT JOIN `'.DB_PREFIX.'promotion` p ON p.promotion_id=ip.promotion_id WHERE p.promotion_code=\''.		_db_sqlspecialchars($name).'\' ';
     $query .= ' AND i.active>0 AND i.show_start<=NOW() AND (i.show_end>=NOW() OR i.show_endless>0) ';
     $query .= ' AND p.article_group>0 OR (';//jeśli jest to grupa artykułów to olewamy daty
     $query .= ' (p.allow_endless>0 AND ip.date_start<=NOW() AND (ip.date_end>=NOW() OR ip.date_endless>0)) OR (ip.date_start<=NOW() AND ip.date_end>=NOW() )';
     $query .= ')';//jeśli nie jest to grupa artykułów to bierzemy daty + sprawdzamy czy jest allown endless
     $query .= ' ORDER BY i.show_start DESC ';
    if ($limit > 0) {
        $query .= 'LIMIT ' . intval($limit);
    }
    return _db_get($query);
}

function promotion_list_item_bydate1($name, $limit=0)
{
    $query = 'SELECT i.* '
    . 'FROM ' . DB_PREFIX . 'item i '
    . 'LEFT JOIN cms_article a ON (i.article_id = a.article_id) '
    . 'JOIN ' . DB_PREFIX . 'item_promotion ip ON (ip.item_id = i.item_id) '
    . 'JOIN ' . DB_PREFIX . 'promotion p ON (p.promotion_id = ip.promotion_id) '
    . 'WHERE promotion_code = ' . _db_string($name) . ' '
    . 'AND i.active > 0 AND i.show_start <= NOW() AND (i.show_end >= NOW() OR i.show_endless > 0) '
    . 'AND (p.article_group > 0 OR ('
    . 'ip.date_start <= NOW() AND (ip.date_end >= NOW() OR p.allow_endless AND ip.date_endless OR ip.date_end = 0)'
    . ')) ORDER BY i.created DESC ';
        $query = 'SELECT i.* FROM (`'.DB_PREFIX.'item` i LEFT JOIN `'.DB_PREFIX.'item_promotion` ip ON ip.item_id=i.item_id) LEFT JOIN `'.DB_PREFIX.'promotion` p ON p.promotion_id=ip.promotion_id WHERE p.promotion_code=\''._db_sqlspecialchars($name).'\' ';
     $query .= ' AND i.active>0 AND i.show_start<=NOW() AND (i.show_end>=NOW() OR i.show_endless>0) ';
     $query .= ' AND p.article_group>0 OR (';//jeśli jest to grupa artykułów to olewamy daty
     $query .= ' (p.allow_endless>0 AND ip.date_start<=NOW() AND (ip.date_end>=NOW() OR ip.date_endless>0)) OR (ip.date_start<=NOW() AND ip.date_end>=NOW() )';
     $query .= ')';//jeśli nie jest to grupa artykułów to bierzemy daty + sprawdzamy czy jest allown endless
     $query .= ' ORDER BY i.show_start DESC ';
     if ($limit > 0) {
        $query .= 'LIMIT ' . intval($limit);
    }
    return _db_get($query);
}

function promotion_list_item_bydate2($name, $limit=0)
{
    $query = 'SELECT i.* '
    . 'FROM ' . DB_PREFIX . 'item i '
    . 'LEFT JOIN cms_article a ON (i.article_id = a.article_id) '
    . 'JOIN ' . DB_PREFIX . 'item_promotion ip ON (ip.item_id = i.item_id) '
    . 'JOIN ' . DB_PREFIX . 'promotion p ON (p.promotion_id = ip.promotion_id) '
    . 'WHERE promotion_code = ' . _db_string($name) . ' '
    . 'AND i.active > 0 AND i.show_start <= NOW() AND (i.show_end >= NOW() OR i.show_endless > 0) '
    . 'AND (p.article_group > 0 OR ('
    . 'ip.date_start <= NOW() AND (ip.date_end >= NOW() OR p.allow_endless AND ip.date_endless OR ip.date_end = 0)'
    . ')) ORDER BY i.modificated DESC ';
        $query = 'SELECT i.* FROM (`'.DB_PREFIX.'item` i LEFT JOIN `'.DB_PREFIX.'item_promotion` ip ON ip.item_id=i.item_id) LEFT JOIN `'.DB_PREFIX.'promotion` p ON p.promotion_id=ip.promotion_id WHERE p.promotion_code=\''._db_sqlspecialchars($name).'\' ';
     $query .= ' AND i.active>0 AND i.show_start<=NOW() AND (i.show_end>=NOW() OR i.show_endless>0) ';
     $query .= ' AND p.article_group>0 OR (';//jeśli jest to grupa artykułów to olewamy daty
     $query .= ' (p.allow_endless>0 AND ip.date_start<=NOW() AND (ip.date_end>=NOW() OR ip.date_endless>0)) OR (ip.date_start<=NOW() AND ip.date_end>=NOW() )';
     $query .= ')';//jeśli nie jest to grupa artykułów to bierzemy daty + sprawdzamy czy jest allown endless
     $query .= ' ORDER BY i.show_start DESC ';
    if ($limit > 0) {
        $query .= 'LIMIT ' . intval($limit);
    }
    return _db_get($query);
}

function promotion_list_item_bynr($name, $limit=0)
{
    $query = 'SELECT i.* '
    . 'FROM ' . DB_PREFIX . 'item i '
    . 'LEFT JOIN cms_article a ON (i.article_id = a.article_id) '
    . 'JOIN ' . DB_PREFIX . 'item_promotion ip ON (ip.item_id = i.item_id) '
    . 'JOIN ' . DB_PREFIX . 'promotion p ON (p.promotion_id = ip.promotion_id) '
    . 'WHERE promotion_code = ' . _db_string($name) . ' '
    . 'AND i.active > 0 AND i.show_start <= NOW() AND (i.show_end >= NOW() OR i.show_endless > 0) '
    . 'AND (p.article_group > 0 OR ('
    . 'ip.date_start <= NOW() AND (ip.date_end >= NOW() OR p.allow_endless AND ip.date_endless OR ip.date_end = 0)'
    . ')) ORDER BY a.order DESC ';
        $query = 'SELECT i.* FROM (`'.DB_PREFIX.'item` i LEFT JOIN `'.DB_PREFIX.'item_promotion` ip ON ip.item_id=i.item_id) LEFT JOIN `'.DB_PREFIX.'promotion` p ON p.promotion_id=ip.promotion_id WHERE p.promotion_code=\''._db_sqlspecialchars($name).'\' ';
     $query .= ' AND i.active>0 AND i.show_start<=NOW() AND (i.show_end>=NOW() OR i.show_endless>0) ';
     $query .= ' AND p.article_group>0 OR (';//jeśli jest to grupa artykułów to olewamy daty
     $query .= ' (p.allow_endless>0 AND ip.date_start<=NOW() AND (ip.date_end>=NOW() OR ip.date_endless>0)) OR (ip.date_start<=NOW() AND ip.date_end>=NOW() )';
     $query .= ')';//jeśli nie jest to grupa artykułów to bierzemy daty + sprawdzamy czy jest allown endless
     $query .= ' ORDER BY i.show_start DESC ';
    if ($limit > 0) {
        $query .= 'LIMIT ' . intval($limit);
    }
    return _db_get($query);
}

function promotion_list_group($lang='', $admin=false)
{
    global $GL_CONF;
    if ($lang=='') {
        $lang = $GL_CONF['DEFAULT_LANG'];
    }
    $query = 'SELECT p.*,t.trans as `name` FROM `'.DB_PREFIX.'promotion` p LEFT JOIN  `'.DB_PREFIX.'trans` t ON p.promotion_name=t.trans_id AND t.lang_id=\''._db_sqlspecialchars($lang).'\' WHERE p.article_group>0 ';
    if (!$admin) {
        $query .= ' AND p.active>0 ';
    }
    $query .= ' ORDER BY name';
    return _db_get($query, 'promotion_id'); //dodać zarz±dzanie orderami
}

function promotion_list_search($lang='', $admin=false)
{
    global $GL_CONF;
    if ($lang=='') {
        $lang = $GL_CONF['DEFAULT_LANG'];
    }
    $query = 'SELECT p.*,t.trans as `name` FROM `'.DB_PREFIX.'promotion` p LEFT JOIN  `'.DB_PREFIX.'trans` t ON p.promotion_name=t.trans_id AND t.lang_id=\''._db_sqlspecialchars($lang).'\' WHERE p.search>0 ';
    if (!$admin) {
        $query .= ' AND p.active>0 ';
    }
    $query .= ' ORDER BY name';
    return _db_get($query, 'promotion_id'); //dodać zarz±dzanie orderami
}

function promotion_list_promo($lang='', $admin=false)
{
    global $GL_CONF;
    if ($lang == '') {
        $lang = $GL_CONF['DEFAULT_LANG'];
    }
    $query = 'SELECT p.*,t.trans as `name` FROM `'.DB_PREFIX.'promotion` p LEFT JOIN  `'.DB_PREFIX.'trans` t ON p.promotion_name=t.trans_id AND t.lang_id=\''._db_sqlspecialchars($lang).'\' WHERE p.article_group=0 ';
    if (!$admin) {
        $query .= ' AND p.active>0 ';
    }
    $query .= ' ORDER BY name';
    return _db_get($query, 'promotion_id'); //dodać zarz±dzanie orderami
}

function promotion_list()
{
    global $GL_CONF;
    return _db_get('SELECT p.*,t.trans as `name` FROM `'.DB_PREFIX.'promotion` p LEFT JOIN  `'.DB_PREFIX.'trans` t ON p.promotion_name=t.trans_id AND t.lang_id=\''._db_sqlspecialchars($GL_CONF['DEFAULT_LANG']).'\' ORDER BY name','promotion_id'); //dodać zarz±dzanie orderami
}

function promotion_get($id)
{
    $res = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'promotion` WHERE promotion_id=' . intval($id) . ' LIMIT 1');
    $res['name'] = trans_get($res['promotion_name']);
    $res['menu_access'] = _db_get('SELECT menu_id FROM `' . DB_PREFIX . 'promotion_menu_access` WHERE promotion_id=' . intval($id));
    // zwraca tablice postaci 'newsletter_group_id' => wiersz
    $res['newsletter_group_access'] = _db_get('SELECT * FROM `' . DB_PREFIX . 'promotion_newsletter_group_access` WHERE promotion_id=' . intval($id),'newsletter_group_id');

    //var_dump($res);
    return $res;
}

function promotion_update($tab)
{
    global $GL_ACCESS_LVL;
    $t = array(
        'promotion_name'=>_db_int(trans_update($tab['name'],$tab['promotion_name'])),
        'promotion_code'=>_db_string($tab['promotion_code']),
        'active'=>_db_bool($tab['active']),
        'search' => _db_bool($tab['search']),
        'rss'=>_db_bool($tab['rss']),
        'article_group'=>_db_bool($tab['article_group']),
        'allow_endless'=>_db_bool($tab['allow_endless']),
    );
    if ($tab['promotion_id'] > 0) {
        $menu_check = array();
        $TMP = promotion_get($tab['promotion_id']);
        $user_menu_access = $TMP['menu_access'];
        foreach ($user_menu_access as $key => $access){
            if (!array_key_exists($access['menu_id'], $tab['allow_menu_access'])) {
                _db_query('DELETE FROM `' . DB_PREFIX . 'promotion_menu_access` WHERE menu_id=' . intval($access['menu_id']) . ' AND promotion_id='.intval($tab['promotion_id']));
            }
            $menu_check[$access['promotion_id']] = 1;
        }

        // przypisanie menu do grupy
        if (is_array($tab['allow_menu_access'])) {
            foreach ($tab['allow_menu_access'] as $menu_id => $menu_access) {
                if (!array_key_exists($menu_id, $menu_check)) {
                    $t_access = array(
                    'promotion_id'=>$tab['promotion_id'],
                    'menu_id'=>$menu_id,
                    );
                    _db_insert('promotion_menu_access', $t_access);
                }
            }
        }

        // przypisanie grup newslettera do grupy
        if (is_array($tab['newsletter_group_access'])) {
            // usuwa przypisania dla danej grupy artykułów
            _db_query('DELETE FROM `' . DB_PREFIX . 'promotion_newsletter_group_access` WHERE promotion_id='.intval($tab['promotion_id']));
            foreach ($tab['newsletter_group_access'] as $group_id => $group_access) {
                $t_access = array(
                    'promotion_id'=>$tab['promotion_id'],
                    'newsletter_group_id'=>$group_id,
                );
                _db_insert('promotion_newsletter_group_access', $t_access);
            }
        }

        return _db_update('promotion', $t, 'promotion_id=' . intval($tab['promotion_id']));
    } else {
        return _db_insert('promotion', $t);
    }
}

function promotion_delete($id)
{
    // promotion można usunąć tylko jak jest puste - nie ma itemów...
    $x = _db_get_one('SELECT * FROM `'.DB_PREFIX.'item_promotion` WHERE promotion_id='.intval($id).' LIMIT 1');
    if ($x['item_id'] > 0) {
        return false;
    } else {

        trans_delete($x['promotion_name']);
        return _db_delete('promotion','promotion_id=' . intval($id), 1);
    }
}

function promotion_validate($tab, $T)
{
    global $GL_CONF;
    $res = array();
    foreach ($GL_CONF['LANG'] as $lang => $v)  {
        if (trim($tab['name'][$lang]) == '') {
            $res['promotion_name'][$lang] = $T['promotion_name_error'];
        }
    }
    if (!preg_match(ADMIN_CODE_REGEX, $tab['promotion_code'])) {
        $res['promotion_code'] = $T['promotion_code_error'];
    }
    return $res;
}

