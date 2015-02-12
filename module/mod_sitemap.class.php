<?php
define('mod_sitemap.class', 1);
if (file_exists('../lib/menu.php')) {
  require_once '../lib/menu.php';
  require_once '../lib/promotion.php';
} else {
  require_once 'lib/menu.php';
  require_once 'lib/promotion.php';
}

class mod_sitemap
{
  function update($tab)
  {
    $return = _db_replace('mod_sitemap', array('module_id'=>_db_int($tab['module_id'])));
    if (empty($tab['module_id']) || $tab['module_id'] == 0) {
      $tab['module_id'] = $return;
    }
    $user_menu_access = $this->get_menu_access($tab['module_id']);
    foreach ($user_menu_access as $key => $access) {
      if (!array_key_exists($access['menu_id'], $tab['mod_allow_menu_access'])) {
        _db_query('DELETE from `'.DB_PREFIX.'mod_sitemap_menu` WHERE menu_id='.intval($access['menu_id']).' and module_id='.intval($tab['module_id']));
      }
      $menu_check[$access['menu_id']] = 1;
    }

    if (is_array($tab['mod_allow_menu_access'])) {
      foreach ($tab['mod_allow_menu_access'] as $menu_id => $menu_access) {
        if (!array_key_exists($menu_id, $menu_check)) {
          $t_access = array(
					'module_id' => $tab['module_id'],
					'menu_id' => $menu_id,
          );
          _db_insert('mod_sitemap_menu', $t_access);
        }
      }
    }
    return $return;
  }
  
function get_menu_access($ID)
  {
    return _db_get('SELECT menu_id FROM `' . DB_PREFIX . 'mod_sitemap_menu` WHERE module_id=' . intval($ID));
  }

  function remove($id)
  {
    return _db_delete('mod_sitemap', 'module_id='.intval($id), 1);
  }

  function validate($tab, $T)
  {
    return true;
  }

  function get($id)
  {
    $res = _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_sitemap` WHERE module_id='.intval($id).' LIMIT 1');
    $res['menu_list'] = menu_list();
    $res['menu_access'] = $this->get_menu_access($id);
    return $res;
  }

  function front($module, $Item)
  {
    $SETTINGS = $this->get($module['module_id']);
    
    echo '<div class="mod_sitemap"><div class="margin"><div class="inside">	';
    foreach ($SETTINGS['menu_access'] as $k => $V) {
      $X = _db_get_one("SELECT menu_code FROM " . DB_PREFIX . "menu WHERE menu_id=" . _db_int($V['menu_id']));
      $menu = new Menu($X['menu_code'], 0, 1, 'map');
      $menu->printTree();
    }
    echo '</div></div></div>';
  }
}
