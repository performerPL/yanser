<?php
require_once '_header.php';
require_once '../lib/user.php';
require_once '../lib/menu.php';
require_once '../lib/promotion.php';
require_once '../lib/article.php';
require_once '../lib/item.php';
_sec_authorise(ACCESS_MIN_EDITOR);
if (get_magic_quotes_gpc()) {
	$_GET = array_map('stripslashes', $_GET);
	$_POST = array_map('stripslashes', $_POST);
}

$Message = '';
$Error = array();
$ID = _get_post('item_id', 0);
$MenuID = _get_post('menu_id', 0);


$Tab = $_POST;
if (!isset($Tab['menu_id'])) {
	$Tab['menu_id'] = $MenuID;
}
if (!isset($Tab['item_id'])) {
	$Tab['item_id'] = $ID;
}

switch($_POST['cmd']) {
	case 'add':
		$Error = item_validate($Tab,$T);

		if (count($Error) == 0) {
			$itemType = $Tab['item_type'];
			// gdy kopia
			if ( $itemType == ITEM_COPY) {
				// ustawia typ na artykuł
				$Tab['item_type'] = ITEM_ARTICLE;
			}
			$x = item_update($Tab);
			// pobiera id przypisanego artykułu
			$article_id = item_get_article_id($x);
			if ( ($itemType == ITEM_COPY) && $Tab['mirror_id'] > 0) {
				item_clone($Tab['mirror_id'], $article_id);
			}
			else if ( ($itemType == ITEM_COPY) && $Tab['mirror_id_a'] > 0) {
				item_clone($Tab['mirror_id_a'], $article_id);
			}

			if ($x > 0) {
				switch ($_POST['next_step']) {
					case NSTEP_NEXT:
					default:
						_redirect('edit_item.php?menu_id='.intval($MenuID).'&item_id='.intval($x).'#content');
						break;

					case NSTEP_SAME:
						_redirect('add_item.php?menu_id='.intval($MenuID).'#content');
						break;

					case NSTEP_PREV:
						_redirect('index_item.php?menu_id='.intval($MenuID).'#i_'.intval($x));
						break;
				}
			} else {
				$Message = $T['update_error_msg'];
			}
		}
		break;

					default:
						break;
}
$Menus = menu_list();
$Items = item_tree($MenuID);

require_once 'tpl/header.html.php';
require_once 'tpl/add_item.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';