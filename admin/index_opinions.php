<?php
require_once('_header.php');
require_once('../lib/gallery.php');
require_once('module/mod_opinions.class.php');
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';
$module = new mod_opinions;

// rodzaj filtrowania aktywnosci - domyślnie = 1 (aktywne)
$activity = !isset($_REQUEST[params][activity]) ? 1 : intval($_REQUEST[params][activity]);
// numer strony - domyślnie = 1
$page = empty($_REQUEST[page]) ? 1 : intval($_REQUEST[page]);
// limit elementów na stronie
$limit = empty($_REQUEST[params][limit]) ? 20 : intval($_REQUEST[params][limit]);
// offset
$offset = ($page-1) * $limit;
// sortowanie
$orderBy = $module->getOrderBy($_REQUEST['order_by'],$_REQUEST['order_type']);
$out[orderType][$_REQUEST['order_by']] = intval($_REQUEST['order_type']);
$out[orderTypeReversed][$_REQUEST['order_by']] = $module->reverseOrderType($_REQUEST['order_type']);

// towrzy tablice z kryteriami do wyszukiwania
$criteria = array();
$criteria[activity] = $activity;
$allRowsNum = count($module->getOpinionModules($criteria,null,null,$orderBy));
$Tab = $module->getOpinionModules($criteria,$limit, $offset,$orderBy);

// tworzy obiekt do paginacji
include_once '../kernel/Paging.php';
$paging = new Paging($allRowsNum, $limit, $page,$linkUrl);

require_once('tpl/header.html.php');
require_once('tpl/index_opinions.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
