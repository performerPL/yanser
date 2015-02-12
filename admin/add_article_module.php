<?php
require_once('_header.php');
require_once('../lib/user.php');
require_once('../lib/article.php');
require_once('../lib/item.php');
_sec_authorise(ACCESS_MIN_EDITOR);


$item_id= _get_post('item_id',0);
$type= _get_post('mod_type',0);
$name= _get_post('mod_name','');

$ArticleID = item_get_article_id($item_id);

if(intval($type)>0 && $name!='') {
	article_mod_add($ArticleID,$type,$name);
}


$Modules = article_mod_list($ArticleID);

header("Content-type: text/html; charset=utf-8");
require_once('tpl/list_article_module.php');

require_once('_footer.php');