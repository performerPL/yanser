<?php
require_once('_header.php');
require_once('../lib/user.php');
require_once('../lib/menu.php');
require_once('../lib/article.php');
require_once('../lib/item.php');
_sec_authorise(ACCESS_MIN_EDITOR);


$item_id= _get_post('id',0);
$x = explode('_',$item_id);
$item_id=intval($x[1]);
// znacznik czy pokazywać archiwalne
$Archive = _get_post('showArchive',true);

$Tab = item_subitem_list($item_id,$Archive,true);

require_once('tpl/index_item_ajaxtree.php');
require_once('_footer.php');
