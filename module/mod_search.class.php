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
		if ($_POST['_search'] == 'true') {
			$this->frontSearch($module, $Item);
		}
	}

	public function getGroups()
	{
		return promotion_list_search();
	}

	function frontForm($module, $Item)
	{
	
		$style = $module['module_style'];
		$SETTINGS = $this->get($module['module_id']);
		if ($SETTINGS['show'] > 0) {
		
		if(isset($_POST['_search_value']) AND empty($_POST['_search_value']))
		{
			echo '<div style="color:red">Proszę o podanie bardziej szczegółwych kryteriów.</div>';
		}
		?>
			
<div class="mod_search box"><div class="margin"><div class="inside">		
<form method="POST"><input type="hidden" name="_search" value="true" /> 
<input	type="text" name="_search_value"	value="<?=htmlspecialchars($_POST["_search_value"])?>" />
<input	type="submit" value="Szukaj" /> 
<?php
	if ($SETTINGS['group'] > 0) {
		$grupki = $_POST['_search_g'];
		if (!is_array($grupki)) {
			$grupki = base64_decode(unserialize($grupki));
			if (!is_array($grupki)) {
				$grupki = array();
			}
		}

		echo '<input type="checkbox" name="_search_tags" value="1" ';
		if ($_POST['_search_tags'] == 1) { echo 'checked="checked"'; }
		echo  '/> szukaj tylko w tagach';
?> 

<?php
		$GROUPS = $this->getGroups();
		echo 'szukaj w grupach:<br /><div class="space"></div>';
		foreach ($GROUPS as $k => $V) {
			echo '<div class="search_group"><input type="checkbox" name="_search_g[]" value="' . $V['promotion_id'] . '" ';
			if (in_array($V['promotion_id'], $grupki)) echo 'checked="checked"';
			echo  '/> ' . $V['name'] . '</div>  ';
		}
		
	
	}
?>

</form>
<div class="space"></div> 
</div></div></div>
	<?php
		}
	}

	function frontSearch($module, $ItemMain)
	{
		global $GL_CONF;
		$cfg = $GL_CONF['IMAGES_FILES'];
		$style = $module['module_style'];
		$SETTINGS = $this->get($module['module_id']);
		$offset = _db_int($_POST['_search_offset']);
		if ($SETTINGS['group'] > 0) {
			$grupki = $_POST['_search_g'];
			if (!is_array($grupki)) {
				$grupki = base64_decode(unserialize($grupki));
			}
		}
		if (!$offset) {
			$offset = 0;
		}
		
		print_r($_POST);
		echo '<hr>';
		print_r($SETTINGS);
		
		$limit = $SETTINGS['count'];
		
		if ($limit === 0) {
			$limit = 1000;
		}
		$n = $this->count($_POST, $SETTINGS);
		$l = $this->search($_POST, $offset, $limit, $SETTINGS);
		if (!count($l)) {
			echo 'Brak wynikow dla podanego zapytania';
		} else {
			echo '<ul class="searchresult">';
			foreach ($l as $item) {
				$item_id = $item['item_id'];
				$Item = new Item($item_id);
				echo '<li>';
				$itemIcon = $Item->getIcon();
				if ($SETTINGS['s_icon'] > 0 && !empty($itemIcon)) {
					$imgHtml = '<img src="' . $cfg['IMAGE_BASE_URL'] . $itemIcon . '"/>';
					echo '<div class="sub_icon">'. $Item->getLink($imgHtml).'</div>';
				}
				if ($SETTINGS['s_title'] > 0) {
					echo '<div class="title">' . $Item->getLink() . '</div>';
				}
				if ($SETTINGS['s_descr'] > 0) {
					echo '<div class="desc">' . $Item->getDescription() . '</div>';
				}
				echo '<div class="space"></div>';
				echo '</li>';
			}
			echo '</ul>';
			$text = $_POST['_search_value'];
			echo '<div class="paging">';
			if ($SETTINGS['group'] > 0) {
				$g_url = '&_search_g=' . base64_encode(serialize($grupki));
			} else {
				$g_url = '';
			}
			if($_POST['_search_tags'] == 1) {
				$g_url .= "&_search_tags=1";
			}
			
			if ($offset >= $limit) {
				echo '<span class="previous"><a href="'. $ItemMain->getID() .'?_search=true' . $g_url . '&_search_value=' . urlencode($text) . '&_search_offset=' . ($offset - $limit) . '">Poprzednie</a></span>';
			}

			if ($n > $limit) {
				for ($i = 0, $j = 1; $i < $n; $i += $limit, $j++) {
					if ($i == $offset) {
						echo '<span class="current">&nbsp;' . $j . '&nbsp;</span>';
					} else {
						echo '<span><a href="'. $ItemMain->getID() .'?_search=true' . $g_url . '&_search_value=' . urlencode($text) . '&_search_offset=' . $i . '">&nbsp;' . $j . '&nbsp;</a></span>';
					}
				}
			}
			if ($offset + $limit < $n) {
				echo '<span class="next"><a href="'. $ItemMain->getID() .'?_search=true' . $g_url . '&_search_value=' . urlencode($text) . '&_search_offset=' . ($offset + $limit) . '">Nastepne</a></span>';
			}
			echo '</div>';
		}
	}

	function search($args, $offset, $limit, $SETTINGS = array())
	{
		$grupki = $_POST['_search_g'];
		if (!is_array($grupki)) {
			$grupki = base64_decode(unserialize($grupki));
			if (!is_array($grupki)) {
				$grupki = array();
			}
		}
		$sql = "SELECT i.item_id FROM cms_item i ";
		if ($SETTINGS['group'] > 0 && count($grupki) > 0) {
			$sql .= "JOIN cms_item_promotion ip ON (i.item_id=ip.item_id) ";
			if (count($grupki) > 0) {
				$sql .= "JOIN cms_promotion p ON (p.promotion_id=ip.promotion_id AND p.search>0 AND p.promotion_id IN (" . implode(',', $grupki) . ")) ";
			} else {
				$sql .= "JOIN cms_promotion p ON (p.promotion_id=ip.promotion_id AND p.search>0 ) ";
			}
		}

		// dodatkowe warunki
		$where = "";
		// warunek dla menu
		if(count($SETTINGS['menu_access']) > 0) {
			$where .= " ( 0 ";
			foreach($SETTINGS['menu_access'] as $menu) {
				$where .= " OR i.menu_id = " . $menu['menu_id'];
			}
			$where .= " ) AND ";
		}

		$sql .= "LEFT JOIN cms_article a ON (i.article_id = a.article_id)"
		. "WHERE "
		. $where ." ";

		if ($_POST['_search_tags'] == 1) {
			$sql .= " a.tags LIKE '%" . _db_sqlspecialchars($args['_search_value']) . "%'";
		}
		else {
			$sql .= "( CONCAT(COALESCE(i.link_url, ''), ' ',"
			. " COALESCE(i.item_code, ''), ' ',"
			. " COALESCE(i.item_name, ''), ' ',"
			. " COALESCE(i.item_long_name, ''), ' ',"
			. " COALESCE(i.item_description, ''), ' ',"
			. " COALESCE(a.meta_description, ''), ' ',"
			. " COALESCE(a.meta_keywords, ''), ' ',"
			. " COALESCE(a.tags, ''), ' ',"
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
			. ") ) ";
		}

		$sql .= "ORDER BY a.order, i.item_name "
		. "LIMIT $offset, $limit";

		return _db_get($sql);
	}

	function count($args, $SETTINGS)
	{
		$grupki = $_POST['_search_g'];
		if (!is_array($grupki)) {
			$grupki = base64_decode(unserialize($grupki));
			if (!is_array($grupki)) {
				$grupki = array();
			}
		}
		$sql = "SELECT COUNT(*) AS \"count\" FROM cms_item i ";
		if ($SETTINGS['group'] > 0 && count($grupki) > 0) {
			$sql .= "JOIN cms_item_promotion ip ON (i.item_id=ip.item_id) ";
			if (count($grupki) > 0) {
				$sql .= "JOIN cms_promotion p ON (p.promotion_id=ip.promotion_id AND p.search>0 AND p.promotion_id IN (" . implode(',', $grupki) . ")) ";
			} else {
				$sql .= "JOIN cms_promotion p ON (p.promotion_id=ip.promotion_id AND p.search>0 ) ";
			}
		}
	
        // dodatkowe warunki
        $where = "";
        // warunek dla menu
        if(count($SETTINGS['menu_access']) > 0) {
            $where .= " ( 0 ";
            foreach($SETTINGS['menu_access'] as $menu) {
                $where .= " OR i.menu_id = " . $menu['menu_id'];
            }
            $where .= " ) AND ";
        }

        $sql .= "LEFT JOIN cms_article a ON (i.article_id = a.article_id)"
        . "WHERE "
        . $where ." ";

        if ($_POST['_search_tags'] == 1) {
            $sql .= " a.tags LIKE '%" . _db_sqlspecialchars($args['_search_value']) . "%'";
        }
        else {
            $sql .= "( CONCAT(COALESCE(i.link_url, ''), ' ',"
            . " COALESCE(i.item_code, ''), ' ',"
            . " COALESCE(i.item_name, ''), ' ',"
            . " COALESCE(i.item_long_name, ''), ' ',"
            . " COALESCE(i.item_description, ''), ' ',"
            . " COALESCE(a.meta_description, ''), ' ',"
            . " COALESCE(a.meta_keywords, ''), ' ',"
            . " COALESCE(a.tags, ''), ' ',"
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
            . ") ) ";
        }
        
		$res = _db_get_one($sql);
		return $res['count'];
	}

}
