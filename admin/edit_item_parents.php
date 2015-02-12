<?php
require_once('_header.php');
require_once('../lib/user.php');
require_once('../lib/menu.php');
require_once('../lib/promotion.php');
require_once('../lib/template.php');
require_once('../lib/article.php');
require_once('../lib/item.php');
_sec_authorise(ACCESS_MIN_EDITOR);

$MenuID = _get_post('menu_id', 0);
$ParentID = _get_post('parent_id', 0);
$ItemID = _get_post('item_id', 0);
$Items = item_tree($MenuID);

require_once('tpl/edit_item_parents.html.php');
