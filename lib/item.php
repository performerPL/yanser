<?php
if (!defined('_APP')) {
	exit;
}
if (defined('_LIB_ITEM.PHP')) {
	return;
}
define('_LIB_ITEM.PHP', 1);

function item_get_simple($item_id, $admin=false)
{
	$res = _db_get_one('SELECT * FROM `'.DB_PREFIX.'item` WHERE item_id='.intval($item_id).' '.($admin?'':' AND active>0 AND show_start<=NOW() AND (show_endless>0 OR show_end>=NOW()) ').'  LIMIT 1');
	if (!$res) {
		return false;
	}
	//echo mysql_error();
	$res['addon'] = _db_get('SELECT * FROM `'.DB_PREFIX.'item_addons` WHERE item_id='.intval($item_id).' LIMIT '.intval(ADDONS_COUNT));
	$res['addon'] = $res['addon'][0];
	return $res;
}

function item_get_history($parent_id) {
	$res = array();
	$hardcore_limit = 10; //ile jest maksymalnie zaglebien w historii
	$level = 0;
	while($level<$hardcore_limit && $parent_id>0) {
		$res[$level] = item_get_simple($parent_id);
		$parent_id = intval($res[$level]['parent_id']);
		++$level;
	}

	return $res;
}

function item_get_orders($parent_id, $menu_id = 0, $order = 'i.item_order',$showArchive = true, $index_key='') {
	$query = 'SELECT i.item_id, i.item_order FROM `'.DB_PREFIX.'item` i '
	. 'LEFT JOIN ' . DB_PREFIX . 'article a ON (i.article_id=a.article_id) '
	. 'WHERE i.parent_id = '.intval($parent_id)
	. ($menu_id ? ' AND i.menu_id = ' . intval($menu_id) : '');

	if(!$showArchive)
	$query .= " AND (show_endless=1 OR show_end>=NOW())";

	$query .= ' ORDER BY ' . $order;
	return _db_get($query, $index_key);
}

function item_get_orders_showgroup($parent_id, $menu_id = 0, $SETTINGS) {

	if ($SETTINGS['pokazuj'] == 0) {
		$order = 'i.show_start DESC';
	} elseif ($SETTINGS['pokazuj'] == 1) {
		$order = 'a.counter';
	}

	$limit = '';
	if ($SETTINGS['wyniki'] > 0) {
		$limit = ' LIMIT ' . $SETTINGS['wyniki'];
	}
	$query = 'SELECT i.item_id, i.item_order FROM `'.DB_PREFIX.'item` i '
	. 'JOIN ' . DB_PREFIX . 'article a ON (i.article_id=a.article_id) '
	. 'JOIN ' . DB_PREFIX . 'item_promotion ip ON (ip.item_id=i.item_id AND ip.promotion_id=' . $SETTINGS['grupa'] . ') '
	. 'WHERE i.item_id != '.intval($parent_id)
	. ($menu_id ? ' AND i.menu_id = ' . intval($menu_id) : '')
	. ' ORDER BY ' . $order . $limit;
	$R = _db_get($query);
	return $R;
}

/**
 * Przebudowuje indeks item_reorder
 *
 * @param $oo Stary układ
 * @param $no Nowy układ
 * @param $parent_id
 * @return unknown_type
 */
function item_reorder($oo,$no,$parent_id) {
	return _db_reorder('item','item_order',$oo,$no,'parent_id',$parent_id);
}

function item_tree($menu_id, $level=0, $archive=false)
{
	if ($level < 0) {
		$level = 0;
	}
	$res = array();
	$cur_level = 1;
	item_tree_level($menu_id, $level, $cur_level, $archive, $res, 0);

	return $res;
}

function item_client_tree_level($menu_id, $max_level, $cur_level, $archive, &$res ,$parents=array(), $cond='',$simpleTab = true)
{
	$parents_var = '';
	$menu_var = '';
	if ($menu_id != '') {
		$menu_var= ' m.menu_code=\''._db_sqlspecialchars($menu_id).'\' AND ';
	}
	if (!is_array($parents)) {
		$parents_var = '=' . intval($parents);
	} else {
		$parents = array_diff($parents,array_keys($res));
		$parents_var =_db_sqlspecialchars(' IN (' . implode(',', $parents) . ')');
	}
	$condition='';
	switch ($cond) {
		case 'menu':
			$condition = ' AND i.hide_in_menu=0 ';
			break;

		case 'map':
			$condition = ' i.hide_in_map=0 ';
			break;

		case 'subitems':
			$condition = ' AND i.hide_in_subitems=0 ';
			break;

		default:
		case '':
			$condition = '';
			break;
	}

	if ($cond == 'map') {
		$query = 'SELECT * FROM `'.DB_PREFIX.'item` i LEFT JOIN `'.DB_PREFIX.'menu` m ON m.menu_id=i.menu_id WHERE '.$menu_var.' ';
		$query .= $condition.' AND i.active>0 AND i.show_start<=NOW() AND (i.show_endless>0 OR i.show_end>=NOW())';
		$query .= ' ORDER BY i.item_order';
	} else {
		$query = 'SELECT * FROM `'.DB_PREFIX.'item` i LEFT JOIN `'.DB_PREFIX.'menu` m ON m.menu_id=i.menu_id WHERE '.$menu_var.' i.parent_id'.$parents_var;
		$query .= $condition.' AND i.active>0 AND i.show_start<=NOW() AND (i.show_endless>0 OR i.show_end>=NOW())';
		$query .= ' ORDER BY i.item_order';
	}

	$result = mysql_query($query);
	_debug(mysql_error(), $query);

	$x = array();
	if ($result) {
		while ($row = mysql_fetch_assoc($result)) {
			if (!is_object($res[$row['parent_id']])) {
				$res[$row['parent_id']] = new ItemList;
			}
			$res[$row['parent_id']]->add(new Item($row, $simpleTab));

			$x[] = $row['item_id'];
		}
		mysql_free_result($result);
	}
	$zwroc = true;
	$go = false;

	if ($max_level <= 0) {
		$go = true;
	} else {
		$go = ($max_level > $cur_level);
	}

	if (count($x) == 0) {
		$go = false;
		$zwroc = false;
	}
	//var_dump($res);
	if ($go) {
		item_client_tree_level($menu_id, $max_level, $cur_level + 1, $archive, $res, $x, $cond);
	} else {
		return $zwroc;
	}
}

function item_tree_level($menu_id, $max_level, $cur_level, $archive, &$res, $parents=array())
{
	/*	echo "item_tree_level($menu_id,$max_level,$cur_level,$archive,$res,$parents)\n";
	 echo "res: ";
	 print_r($res);
	 echo "parents: ";
	 print_r($parents);
	 echo "-----\n";*/

	$parents_var = '';
	if (!is_array($parents)) {
		$parents_var = '='.intval($parents);
	} else {
		$parents = array_diff($parents,array_keys($res));
		$parents_var =_db_sqlspecialchars(' IN ('.implode(',',$parents).')');
	}

	$query = 'SELECT *,IF(active>0 AND show_start<=NOW() AND (show_endless>0 OR show_end>=NOW()),1,0) as visible '
	. 'FROM `'.DB_PREFIX.'item` WHERE menu_id='.intval($menu_id).' AND parent_id'.$parents_var;
	if (!$archive) {
		$query .= ' AND (show_endless=1 OR show_end>=NOW())';
	}
	$query .= ' ORDER BY item_order';

	$result = mysql_query($query);
	_debug(mysql_error(), $query);

	$x = array();
	if ($result) {
		while ($row = mysql_fetch_assoc($result)) {
			if (!is_array($res[$row['parent_id']]['subitems'])) {
				$res[$row['parent_id']]['subitems'] = array();
			}
			$res[$row['parent_id']]['subitems'][$row['item_id']] = $row;

			$x[] = $row['item_id'];
		}
		mysql_free_result($result);
	}
	$zwroc = true;
	$go = false;

	if ($max_level <= 0) {
		$go = true;
	} else {
		$go = ($max_level > $cur_level);
	}

	if (count($x)==0) {
		$go = false;
		$zwroc= false;
	}

	if ($go) {
		item_tree_level($menu_id, $max_level, $cur_level+1, $archive, $res, $x);
	} else {
		return $zwroc;
	}
}

function item_stats($menu_id)
{
	$res = array();

	$x = _db_get_one('SELECT COUNT(item_id) AS wynik FROM `'.DB_PREFIX.'item` WHERE menu_id='.intval($menu_id));
	$res['all'] = intval($x['wynik']);

	$x = _db_get_one('SELECT COUNT(item_id) AS wynik FROM `'.DB_PREFIX.'item` WHERE menu_id='.intval($menu_id).' AND active>0 AND show_start<=NOW() AND (show_endless>0 OR show_end>=NOW())');
	$res['visible'] = intval($x['wynik']);

	$x = _db_get_one('SELECT COUNT(item_id) AS wynik FROM `'.DB_PREFIX.'item` WHERE menu_id='.intval($menu_id).' AND active<=0 ');
	$res['unpublished'] = intval($x['wynik']);

}

function item_get($id, $admin=false)
{
	// trzeba jeszcze pobierać inforamcje o artykule, grupach i addonsach
	$sql = 'SELECT * FROM `'.DB_PREFIX.'item` WHERE item_id='.intval($id).' '.($admin?'':' AND active>0 AND show_start<=NOW() AND (show_endless>0 OR show_end>=NOW()) ').'  LIMIT 1';
	$res = _db_get_one($sql);

	if ($res['article_id'] > 0) {
		$res['article'] = _db_get_one('SELECT * FROM `'.DB_PREFIX.'article` WHERE article_id='.intval($res['article_id']).' LIMIT 1');
		$res['article']['content'] = article_mod_list($res['article_id']);
	}
	$res['addon'] = _db_get('SELECT * FROM `'.DB_PREFIX.'item_addons` WHERE item_id='.intval($id).' LIMIT '.intval(ADDONS_COUNT));
	$res['addon'] = $res['addon'][0];
	$res['group'] = _db_get('SELECT ip.* FROM `'.DB_PREFIX.'item_promotion` ip LEFT JOIN `'.DB_PREFIX.'promotion` p ON ip.promotion_id=p.promotion_id '.($admin?'':' AND p.active>0 AND p.article_group>0 ').' WHERE ip.item_id='.intval($id),'promotion_id');

	return $res;
}

function get_menu_code ($menu_id)
{
		$q = "SELECT menu_code FROM " . DB_PREFIX . "menu WHERE menu_id=" . $menu_id;
		$page = _db_get_one($q);
		if ($page !== false) {
			return $page;
		}
}

function get_item_start($menus = array())
{
	foreach ($menus as $k => $V) {
		$q = "SELECT menu_id FROM " . DB_PREFIX . "menu WHERE menu_code=" . _db_string($V);
		$M = _db_get_one($q);
		$q = "SELECT item_id, item_name FROM " . DB_PREFIX . "item WHERE menu_id=" . _db_int($M['menu_id']) . " AND page_start=1";
		$page = _db_get_one($q);
		if (!empty($page)) {
			return $page;
		}
	}
	return array(null, null);
}

function item_get_article_id($item_id) {
	$res = _db_get_one('SELECT * FROM `'.DB_PREFIX.'item` WHERE item_id='.intval($item_id).' LIMIT 1');
	return intval($res['article_id']);
}

function item_validate($tab, $T)
{
	/*
	 nazwa
	 typ - dla typu:
	 - mirror - target
	 - link - target
	 - link out- url
	 */
	$res = array();


	/*	if(intval($tab['menu_id'])<=0) {
	 $res['menu_id'] = $T['item_menu_id_error'];
	 }*/
	if($tab['item_code']!='') {
		//trzeba sprawdzić, czy taki kod nie jest wykorzystany - w danym itemie
		if(!preg_match('/^[a-zA-Z0-9\-_]*$/',$tab['item_code'])) {
			$res['item_code'] = $T['item_code_error'];
		} else {
			$test = _db_get_one('SELECT * FROM `'.DB_PREFIX.'item` WHERE  menu_id='.intval($tab['menu_id']).' AND item_id<>'.intval($tab['item_id']).' AND parent_id='.intval($tab['parent_id']).' AND item_code=\''._db_sqlspecialchars($tab['item_code']).'\' LIMIT 1');
			if(is_array($test) && $test['item_id']>0) {
				$res['item_code'] = $T['item_code_error1'];
			}
		}
	}
	if(trim($tab['item_name'])=='') {
		$res['item_name'] = $T['item_name_error'];
	}
	if($tab['item_type']!=ITEM_ARTICLE) {
		switch($tab['item_type']) {
			case ITEM_MIRROR:
			case ITEM_LINK_IN:
				if(intval($tab['target_id'])<=0 || $tab['target_id'] == $tab['item_id']) {
					$res['target_id'] = $T['item_target_id_error'];
				} else {
					$test = _db_get_one('SELECT item_type FROM `'.DB_PREFIX.'item` WHERE item_id='.intval($tab['target_id']).' LIMIT 1');
					if($test['item_type']!=ITEM_ARTICLE)  {
						$res['target_id'] = $T['item_target_id_error1'];
					}
				}
				break;
			case ITEM_LINK_OUT:
				if(trim($tab['link_url'])=='') {
					$res['link_url'] = $T['item_link_url_error'];
				}
				break;
		}
	}
	return $res;
}

function inner_change_descendants_menu_id($item_id, $menu_id)
{
	$sql = 'UPDATE ' . DB_PREFIX . 'item SET menu_id = ' . intval($menu_id) . ' WHERE parent_id = ' . intval($item_id);
	_db_query($sql);
	$sql = 'SELECT item_id FROM ' . DB_PREFIX . 'item WHERE parent_id = ' . intval($item_id);
	$res = _db_get($sql);
	foreach($res as $item_id) {
		inner_change_descendants_menu_id($item_id['item_id'], $menu_id);
	}
}

function item_update_menu($parent_id, $menu_id)
{
	$X = _db_get('SELECT parent_id, item_id FROM ' . DB_PREFIX . 'item WHERE parent_id=' . intval($parent_id));
	if ($X === false) {
		return null;
	}
	foreach ($X as $k => $V) {
		if ($V['parent_id'] != 0 && $V['parent_id'] != $parent_id) {
			item_update_menu($V['parent_id'], $menu_id);
		}
		_db_query("UPDATE " . DB_PREFIX . "item SET menu_id=" . intval($menu_id) . " WHERE item_id=" . intval($V['item_id']) . ' LIMIT 1');
	}
}

function item_update($tab)
{
	//global $GL_CONF;
	if ($tab['item_id'] > 0) {
		$item = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'item` WHERE item_id=' . intval($tab['item_id']) . ' LIMIT 1');

		/*mam wszystko w następującej postaci:
		 tab[group][x] = 'date_start',date_end, date_endless'
		 */
		$query = '';
		foreach($tab['group'] as $k=>$v) {
			$query .= ',(';
			$query .= intval($k).',';
			$query .= intval($tab['item_id']).',';
			$query .= intval($v['date_endless']).',';
			$query .= '\''._db_sqlspecialchars($v['date_start']).'\',';
			$query .= '\''._db_sqlspecialchars($v['date_end']).'\'';
			$query .= ')';
		}
		$query = ($query != '' ? substr($query, 1) : '');
		_db_delete('item_promotion', 'item_id=' . intval($tab['item_id']));
		if ($query != '') {
			_db_query('INSERT INTO `' . DB_PREFIX . 'item_promotion`(promotion_id,item_id,date_endless,date_start,date_end) VALUES ' . $query);
		}

		$addons = array();
		for ($i=0; $i < ADDONS_COUNT; ++$i) {
			$addons['add' . $i] = _db_string($tab['addon']['add' . $i]);
		}
		_db_update('item_addons', $addons, 'item_id=' . intval($tab['item_id']));
		//echo 1;

		$tab['article_id'] = 0;
		if ($tab['item_type']==ITEM_MIRROR)  {
			$mirr = _db_get_one('SELECT article_id FROM `'.DB_PREFIX.'item` WHERE item_id='.intval($tab['target_id']).' LIMIT 1');
			$tab['article_id'] = intval($mirr['article_id']);

			if ($tab['item_type']!=$item['item_type']) { //jestem mirrorem - nie byłem - trzeba sprawdzić, czy mój artykuł jest gdzieś używany albo zostawić artykuł w spokoju, tylko przepisać id-ka do innej kolumny i być może przywrócić w miarę potrzeby
				$tab['orig_article_id'] = $item['article_id']; //zachowanie artykułu
			} else {
				$tab['orig_article_id'] = $item['orig_article_id'];
			}
		} else {
			if ($item['item_type']==ITEM_MIRROR)  { //nie jestem mirror, ale byłem - utworzenie artykułu albo przywrócenie id-ka
				if ($item['orig_article_id']>0) {
					$tab['article_id'] = $item['orig_article_id'];
					$tab['orig_article_id'] = $item['orig_article_id'];
				} else {
					$tab['article_id'] = article_create();
					$tab['orig_article_id'] = $tab['article_id'];
				}
			} else { //nie jestem mirrorem, nie byłem mirrorem
				$tab['article_id'] = $item['article_id'];
				$tab['orig_article_id'] = $item['orig_article_id'];
			}
		}

		if($tab['article_id']>0) {
			$art = array(
				'template_id'=>_db_int($tab['article']['template_id']),
				'meta_description'=>_db_string($tab['article']['meta_description']),
				'meta_keywords'=>_db_string($tab['article']['meta_keywords']),
				'author'=>_db_string($tab['article']['author']),
				'show_author'=>_db_bool($tab['article']['show_author']),
                'order' => _db_dec($tab['article']['order']),
                'author_source' => _db_string($tab['article']['author_source']),
                'author_source_name' => _db_string($tab['article']['author_source_name']),
			    'tags' => _db_string($tab['article']['tags'])
			);
			_db_update('article',$art,'article_id='.intval($tab['article_id']));
		}

		$q = array(
			'parent_id'=>_db_int($tab['parent_id']),
    		'menu_id'=>_db_int($tab['menu_id']),
			'item_type'=>_db_int($tab['item_type']),
			'target_id'=>_db_int($tab['target_id']),
			'article_id'=>_db_int($tab['article_id']),
			'orig_article_id'=>_db_int($tab['orig_article_id']),
			'link_url'=>_db_string($tab['link_url']),
			'link_target'=>_db_int($tab['link_target']),
			'item_code'=>_db_string($tab['item_code']),
			'item_name'=>_db_string($tab['item_name']),
			'item_long_name'=>_db_string($tab['item_long_name']),
			'item_description'=>_db_string($tab['item_description']),
			'item_icon'=>_db_string($tab['item_icon']),
      'item_meta_title' => _db_string($tab['item_meta_title']),
			'show_start'=>_db_time($tab['show_start']),
			'show_endless'=>_db_bool($tab['show_endless']),
			'show_end'=>_db_time($tab['show_end']),
            'access_level' => _db_int($tab['access_level']),
			'active'=>_db_bool($tab['active']),
			'hide_in_map'=>_db_bool($tab['hide_in_map']),
			'hide_in_menu'=>_db_bool($tab['hide_in_menu']),
			'hide_in_subitems'=>_db_bool($tab['hide_in_subitems']),
			'show_created'=>_db_bool($tab['show_created']),
			'show_modificated'=>_db_bool($tab['show_modificated']),
			'modificated'=>_db_time('',true),
    		'modificated_by'=>_db_int(_sec_user('user_id')),
    		'modificated_by_name'=>_db_string(_sec_user('user_name')),
            'page_start' => _db_int($tab['page_start']),
            'show_author' => _db_int($tab['show_author'])
		);

		if ($tab['page_start'] > 0) {
			$qq = array(
        'page_start' => 0
			);
			_db_update('item', $qq, 'menu_id='._db_int($tab['menu_id']), '', 0);
		}

		item_update_menu($tab['item_id'], $q['menu_id']);

		if($tab['active']!=$item['active']) {
			$q['activated'] = _db_time('',true);
			$q['activated_by']=_db_int(_sec_user('user_id'));
			$q['activated_by_name']=_db_string(_sec_user('user_name'));
		}
		if($tab['parent_id']==$item['parent_id']) {
			// nothing changes, we cannot change order in this action
		} else {
			// another parent, lets fetch him and check if change is possible (no loops allowed)
			$parent = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'item` WHERE item_id=' . intval($tab['parent_id']) . ' LIMIT 1');
			if(!$parent && $tab['parent_id'] == 0)
			$parent = array(
					"item_id" => 0,
					"menu_id" => $tab['menu_id']
			);
			if (!$parent) {
				die('no such parent: ' + $tab['parent_id']);
			}
			$tmp = $parent;
			while($tmp) {
				if ($tmp['item_id'] == $item['item_id'])
				die('loop encountered when trying to put ' . $item['item_id'] . ' under ' . $tab['parent_id']);
				$tmp = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'item` WHERE item_id=' . intval($tmp['parent_id']) . ' LIMIT 1');
			}
		}

		$res = _db_update('item',$q,'item_id='.intval($tab['item_id']));

		if($res && $tab['parent_id']!=$item['parent_id']) {
			if($item['parent_id']>0) {
				_db_query('UPDATE `'.DB_PREFIX.'item` SET children=children-1 WHERE item_id='.intval($item['parent_id']).' LIMIT 1');
			}
			if($tab['parent_id']>0) {
				_db_query('UPDATE `'.DB_PREFIX.'item` SET children=children+1 WHERE item_id='.intval($tab['parent_id']).' LIMIT 1');
			}
		}

		return $res;
		
	} 
	// nowy wpis
	else {
		// trzeba pobrać article_id
		$tab['article_id'] = 0;
		$tab['orig_article_id'] = 0;
		if($tab['item_type']==ITEM_MIRROR)  {
			$mirr = _db_get_one('SELECT article_id FROM `'.DB_PREFIX.'item` WHERE item_id='.intval($tab['target_id']).' LIMIT 1');
			$tab['article_id'] = intval($mirr['article_id']);
		} else { // utwórz artykuł
			$tab['orig_article_id'] = article_create();
			$tab['article_id'] = $tab['orig_article_id'];
		}
		$parent = _db_get_one('SELECT * FROM `' . DB_PREFIX . 'item` WHERE item_id=' . intval($tab['parent_id']) . ' LIMIT 1');
		if(!$parent)
		$parent = array('menu_id' => $tab['menu_id']);
		// end of quick workaround
		// dodanie nowej tablicy - jedyny problem to wyliczenie kolejnosci - nowy item wstawiany jest domyślnie na koniec
		// pobiera najwiekszy parametr item_order z grupy itemów dla danego parenta
		$itemOrder = _db_get_one('SELECT MAX(item_order) AS `max` FROM `' . DB_PREFIX . 'item` WHERE parent_id=' . intval($tab['parent_id']));
		$itemOrder = ($itemOrder[max] +1);
		$q = array(
			'parent_id'=>_db_int($tab['parent_id']),
			'menu_id'=>_db_int($parent['menu_id']),
			'item_type'=>_db_int($tab['item_type']),
			'target_id'=>_db_int($tab['target_id']),
			'article_id'=>_db_int($tab['article_id']),
			'orig_article_id'=>_db_int($tab['orig_article_id']),
			'link_url'=>_db_string($tab['link_url']),
			'link_target'=>_db_int($tab['link_target']),
			'item_code'=>_db_string($tab['item_code']),
    'item_meta_title' => _db_string($tab['item_meta_title']),
			'item_name'=>_db_string($tab['item_name']),
			'item_long_name'=>_db_string($tab['item_long_name']),
			'item_description'=>_db_string($tab['item_description']),
			'item_icon'=>_db_string($tab['item_icon']),
			'show_start'=>_db_time('',true),
			'show_endless'=>_db_bool(true),
			'created'=>_db_time('',true),
			'created_by'=>_db_int(_sec_user('user_id')),
			'created_by_name'=>_db_string(_sec_user('user_name')),
            'access_level' => _db_int($tab['access_level']),
            'page_start' => _db_int($tab['page_start']),
    		'show_author' => _db_int($tab['show_author']),
			'item_order' => $itemOrder
		);
		if ($tab['page_start'] > 0) {
			$qq = array(
        'page_start' => 0
			);
			_db_update('item', $qq, 'menu_id='._db_int($tab['menu_id']), '', 0);
		}
		//var_dump($q);
		$x = _db_insert('item',$q);
		if($x>0 && $tab['parent_id']>0) {
			_db_query('UPDATE `'.DB_PREFIX.'item` SET children=children+1 WHERE item_id='.intval($tab['parent_id']).' LIMIT 1');
		}
		_db_insert('item_addons',array('item_id'=>_db_int($x)));
		return $x;

	}
}

function item_subitem_list($id, $archive=false, $admin=false)
{

	$query = 'SELECT *,IF(active>0 AND show_start<=NOW() AND (show_endless>0 OR show_end>=NOW()),1,0) as visible FROM `'.DB_PREFIX.'item` WHERE parent_id='.intval($id);
	if (!$archive) {
		$query .= ' AND (show_endless=1 OR show_end>=NOW())';
	}
	if (!$admin) {
		$query .= ' AND active>0 AND show_start<=NOW() ';
	}
	$query .= ' ORDER BY item_order';
	//var_dump($query);
	return _db_get($query,'item_id');

}

function item_delete($id)
{
	$item = item_get_simple($id, true);
	
	if (!$item || $item['children']) {
		return false;
	}
	//usuwa addonsy, grupy, uprawnienia
	if (!_db_delete('item_addons', 'item_id = ' . _db_int($id))) {
		return false;
	}
	if (!_db_delete('item_promotion', 'item_id = ' . _db_int($id))) {
		return false;
	}
	/*if(!_db_delete('user_access', 'object_id = ' . _db_int($id) . ' AND object_type = ' . stala od menu))
	 return false;
	 */
	// nie sprawdzamy dzieci, zabrionione
	//sprawdza, czy usunac artykul
	$sql = 'SELECT COUNT(*) AS "sum" FROM ' . DB_PREFIX . 'item '
	. 'WHERE article_id = ' . _db_int($item['article_id']);
	$sum = _db_get_one($sql);
	if ($sum["sum"] == 1) {
		article_delete($item['article_id']);
	}
	//usuwa item - zmienia kolejnosc
	if (!_db_delete('item', 'item_id = ' . _db_int($id))) {
		return false;
	}
	item_reorder($item['item_order'], _db_new_order('item','item_order','parent_id',$item['parent_id']), $item['parent_id']);
	// zmienia ilosc dzieci parenta
	$sql = 'UPDATE ' . DB_PREFIX . 'item '
	. 'SET children = children - 1 '
	. 'WHERE item_id = ' . _db_int($item['parent_id']);
	return _db_query($sql);
}

/**
 * Kolonuje moduły dla danego itema.
 *
 * @param $cloneItemId
 * @param $articleId Id stworzonego artykułu.
 * @return unknown_type
 */
function item_clone($cloneItemId,$articleId) {
	// pobiera listę modułów
	$modList = article_mod_list($cloneItemId);

	if(!empty($modList)) {
		foreach($modList as $mod) {
			// nadaje nowy id artykułu
			$mod['article_id'] = $articleId;
			article_mod_add_clone($mod);
		}
	}
}