<?php

define('mod_search.class', 1);
if (file_exists('../lib/menu.php')) {
require_once '../lib/menu.php';
require_once '../lib/promotion.php';
} else {
  require_once 'lib/menu.php';
  require_once 'lib/promotion.php';
}

class mod_search
{
  function update($tab)
  {
    		$return = _db_replace('mod_search', array(
     'module_id' => _db_int($tab['module_id']),
     'show' => _db_int($tab['show']),
     'information' => _db_string($tab['information']),
     'count' => _db_int($tab['count']),
     'modules' => _db_string($tab['modules']),
    'group' => _db_int($tab['group']),
    's_title' => _db_int($tab['s_title']),
    's_descr' => _db_int($tab['s_descr']),
    's_icon' => _db_int($tab['s_icon']),
    'group' => _db_int($tab['group']),
     ));
     
   if (empty($tab['module_id']) || $tab['module_id'] == 0) {
     $tab['module_id'] = $return;
   }
   $user_menu_access = $this->get_menu_access($tab['module_id']);
      foreach ($user_menu_access as $key => $access) {
        if (!array_key_exists($access['menu_id'], $tab['mod_allow_menu_access'])) {
          _db_query('DELETE from `'.DB_PREFIX.'mod_search_menu` WHERE menu_id='.intval($access['menu_id']).' and module_id='.intval($tab['module_id']));
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
            _db_insert('mod_search_menu', $t_access);
          }
        }
      }
      
      return $return;
  }

  function remove($id)
  {
    return _db_delete('mod_search', 'module_id=' . intval($id), 1);
  }

  function validate($tab, $T)
  {
    //		return $tab['gallery_id'] > 0;
  }

  function get_menu_access($ID)
  {
    return _db_get('SELECT menu_id FROM `' . DB_PREFIX . 'mod_search_menu` WHERE module_id=' . intval($ID));
  }
  
  function get($id)
  {
    $res = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_search` WHERE module_id=' . intval($id) . ' LIMIT 1');
    $res['menu_list'] = menu_list();
    $res['menu_access'] = $this->get_menu_access($id);
    return $res;		
  }

  function front($module, $Item)
  {
    //		$data = $this->get($module['module_id']);
    //		if (!$data) {
    //			return;
    //	  }
      $this->frontForm($module, $Item);
      if ($_REQUEST['_search'] == 'true') {
        $this->frontSearch($module, $Item);
      }
    }

  function frontForm($module, $Item)
    {
      $style = $module['module_style'];
      ?>
<form method="GET"><input type="hidden" name="_search" value="true" /> <input
	type="text" name="_search_value"
	value="<?=htmlspecialchars($_REQUEST["_search_value"])?>" /> <input
	type="submit" value="Szukaj" /></form>
      <?
}

function frontSearch($module, $Item)
{
  $style = $module['module_style'];
  $offset = _db_int($_REQUEST["_search_offset"]);
  if (!$offset) {
    $offset = 0;
  }
  $limit = 5;
  $n = $this->count($_REQUEST);
  $l = $this->search($_REQUEST, $offset, $limit);
  if (!count($l)) {
    echo 'Brak wynikow dla podanego zapytania';
  } else {
    echo '<ul class="searchresult">';
    foreach ($l as $item) {
      $item_id = $item['item_id'];
      $Item = new Item($item_id);
      echo '<li>';
      echo '<div class="sub_icon"><img src="' . $Item->getIcon() . '"/></div>';
      echo '<div class="title">' . $Item->getLink() . '</div>';
      echo '<div class="desc">' . $Item->getDescription() . '</div>';
      echo '<div class="space"></div>';
      echo '</li>';
    }
    echo '</ul>';
    $text = $_REQUEST['_search_value'];
    echo '<div class="paging">';
    if ($offset >= $limit) {
      echo '<span class="previous"><a href="?_search=true&_search_value=' . urlencode($text) . '&_search_offset=' . ($offset - $limit) . '">Poprzednie</a></span>';
    }
    for ($i = 0, $j = 1; $i < $n; $i += $limit, $j++) {
      if ($i == $offset) {
        echo '<span class="current">&nbsp;' . $j . '&nbsp;</span>';
      } else {
        echo '<span><a href="?_search=true&_search_value=' . urlencode($text) . '&_search_offset=' . $i . '">&nbsp;' . $j . '&nbsp;</a></span>';
      }
    }
    if ($offset + $limit < $n) {
      echo '<span class="next"><a href="?_search=true&_search_value=' . urlencode($text) . '&_search_offset=' . ($offset + $limit) . '">Nastepne</a></span>';
    }
    echo '</div>';
  }
}

function search($args, $offset, $limit)
{
  $sql = "SELECT i.item_id FROM cms_item i "
  . "LEFT JOIN cms_article a ON (i.article_id = a.article_id)"
  . "WHERE "
  . " CONCAT(COALESCE(i.link_url, ''), ' ',"
  . " COALESCE(i.item_code, ''), ' ',"
  . " COALESCE(i.item_name, ''), ' ',"
  . " COALESCE(i.item_long_name, ''), ' ',"
  . " COALESCE(i.item_description, ''), ' ',"
  . " COALESCE(a.meta_description, ''), ' ',"
  . " COALESCE(a.meta_keywords, ''), ' ',"
  . " COALESCE(a.author, '')) LIKE '%" . _db_sqlspecialchars($args['_search_value']) . "%'"
  . "OR EXISTS ("
  . " SELECT * FROM cms_article_content c"
  . " LEFT JOIN cms_mod_text t ON (c.module_id = t.text_id)"
  . " LEFT JOIN cms_mod_gallery mg ON (c.module_id = mg.module_id)"
  . "  LEFT JOIN cms_gallery g ON (mg.gallery_id = g.gallery_id)"
  . "   LEFT JOIN cms_gallery_pic gp ON (g.gallery_id = gp.gallery_id)"
  . " WHERE"
  . "  c.article_id = i.article_id"
  . " AND CONCAT("
  . "  COALESCE(c.module_name, ''), ' ',"
  . "  COALESCE(t.html_text, ''), ' ',"
  . "  COALESCE(g.gallery_name, ''), ' ',"
  . "  COALESCE(g.gallery_description, ''), ' ',"
  . "  COALESCE(gp.picture_title, ''), ' ',"
  . "  COALESCE(gp.picture_description, '')) LIKE '%" . _db_sqlspecialchars($args['_search_value']) . "%'"
  . ") "
  . "ORDER BY i.item_name "
  . "LIMIT $offset, $limit";
  return _db_get($sql);
}

function count($args)
{
  $sql = "SELECT COUNT(*) AS \"count\" FROM cms_item i "
  . "LEFT JOIN cms_article a ON (i.article_id = a.article_id)"
  . "WHERE "
  . " CONCAT(COALESCE(i.link_url, ''), ' ',"
  . " COALESCE(i.item_code, ''), ' ',"
  . " COALESCE(i.item_name, ''), ' ',"
  . " COALESCE(i.item_long_name, ''), ' ',"
  . " COALESCE(i.item_description, ''), ' ',"
  . " COALESCE(a.meta_description, ''), ' ',"
  . " COALESCE(a.meta_keywords, ''), ' ',"
  . " COALESCE(a.author, '')) LIKE '%" . _db_sqlspecialchars($args['_search_value']) . "%'"
  . "OR EXISTS ("
  . " SELECT * FROM cms_article_content c"
  . " LEFT JOIN cms_mod_text t ON (c.module_id = t.text_id)"
  . " LEFT JOIN cms_mod_gallery mg ON (c.module_id = mg.module_id)"
  . "  LEFT JOIN cms_gallery g ON (mg.gallery_id = g.gallery_id)"
  . "   LEFT JOIN cms_gallery_pic gp ON (g.gallery_id = gp.gallery_id)"
  . " WHERE"
  . "  c.article_id = i.article_id"
  . " AND CONCAT("
  . "  COALESCE(c.module_name, ''), ' ',"
  . "  COALESCE(t.html_text, ''), ' ',"
  . "  COALESCE(g.gallery_name, ''), ' ',"
  . "  COALESCE(g.gallery_description, ''), ' ',"
  . "  COALESCE(gp.picture_title, ''), ' ',"
  . "  COALESCE(gp.picture_description, '')) LIKE '%" . _db_sqlspecialchars($args['_search_value']) . "%'"
  . ") ";
		$res = _db_get_one($sql);
		return $res['count'];
	}
	
}
