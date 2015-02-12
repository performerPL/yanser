<?php
require_once '_header.php';
require_once '../lib/user.php';
require_once '../lib/menu.php';
require_once '../lib/promotion.php';
require_once '../lib/template.php';
require_once '../lib/article.php';
require_once '../lib/item.php';
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';
$Error = array();
$ID = _get_post('item_id', 0);
$MenuID = _get_post('menu_id', 0);


$getItem = item_get_simple($ID);
$articleModuleOrder = article_mod_list($getItem['article_id']);
$intrForLoop = 1;
foreach($articleModuleOrder as $key=>$row)
{
	article_mod_update_order($intrForLoop, $key);
	$intrForLoop++;
}
unset($intrForLoop);

if ($ID == 0) {
	_redirect('add_item.php?item_id=0&menu_id=' . intval($MenuID) . '#content');
}

$Tab = $_POST;

$groups = promotion_list_group('', true);
$promotions = promotion_list_promo('', true);

switch ($_POST['cmd']) {
	case 'edit':
		//trzeba jeszcze postawiać grupy...
		$Tab['group'] = array();
		if (count($_POST['promo']) > 0) {
			foreach ($_POST['promo'] as $i => $k) {
				$Tab['group'][$k] = array(
					'date_start' => $_POST['promo_' . intval($k) . '_start'],
					'date_end' => $_POST['promo_' . intval($k) . '_end'],
					'date_endless' => $_POST['promo_' . intval($k) . '_endless'],
				);
			}
		}
		if (count($_POST['groups']) > 0) {
			foreach ($_POST['groups'] as $i => $k) {
				$Tab['group'][$k] = array();
			}
		}
		$Error = item_validate($Tab, $T);
		//var_dump($Error);
		if (count($Error) == 0) {
			$x = item_update($Tab);
			if ($x) {
				//_redirect('index_item.php?menu_id=' . intval($MenuID) . '#i_' . intval($x)); requested
			} else {
				$Message = $T['update_error_msg'];
			}
		}
		break;

	case 'delete':
		if (intval($ID) > 0 && item_delete($ID)) {
			_redirect('index_item.php?menu_id=' . intval($MenuID) . '#content');
		} else {
			$Message = $T['delete_error_msg'];
		}
		break;

	default:
		break;
}
if ($ID > 0) {
	// przy łączeniu tablic pomija pole article - ono jest już poprawne w tablicy $in
	$Tab = _merge(item_get($ID, true), $Tab,array("article"));
	
	$Stats = array(
	$T['id'] => $Tab['item_id'],
	$T['created'] => $Tab['created'],
	$T['created_by_name'] => $Tab['created_by_name'],

	);

	if ($Tab['activated_by'] > 0) {
		if ($Tab['active'] > 0) {
			$Stats[$T['activated']] = $Tab['activated'];
			$Stats[$T['activated_by_name']] = $Tab['activated_by_name'];
		} else {
			$Stats[$T['deactivated']] = $Tab['activated'];
			$Stats[$T['deactivated_by_name']] = $Tab['activated_by_name'];
		}
	}
	if ($Tab['article_id'] > 0) {
		$Stats[$T['article_showed']] = intval($Tab['article']['visits']);
		$Stats[$T['article_commented']] = intval($Tab['article']['comments']);
		$Stats[$T['article_noted']] = $Tab['article']['note'];
		$Stats[$T['article_votes']] = intval($Tab['article']['votes']);
	}
	$MenuID = $Tab['menu_id'];
}
$Tab['groups'] = array();
$Tab['promo'] = array();



$Menus = menu_list();
$Items = item_tree($MenuID);

$Templates = (array(0 => $T['item_template_def']) + template_list_mid($MenuID));

/*
 $allItems = array();
 $allItems[0] = array();
 $allItems[0]['subitems'] = array();
 foreach($Menus as $menu) {
 $tmp = item_tree($menu["menu_id"]);
 if(isset($tmp[0]) && isset($tmp[0]['subitems']))
 $allItems[0]['subitems'] = array_merge($allItems[0]['subitems'], $tmp[0]['subitems']);
 }*/



$AccessLevel = array(
0 => 0,
2 => 2,
4 => 4,
6 => 6,
8 => 8
);

require_once 'tpl/header.html.php';
require_once 'tpl/edit_item.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';