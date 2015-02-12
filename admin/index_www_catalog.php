<?php
require_once '_header.php';
require_once 'class/WwwCatalog.class.php';
require_once '../lib/www_catalog.php';
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';

$wwwCatalog = new WwwCatalog();

// rodzaj filtrowania  - domyślnie = 1 (aktywne)
$show = !isset($_REQUEST[params][show]) ? 1 : intval($_REQUEST[params][show]);
// numer strony - domyślnie = 1
$page = empty($_REQUEST[page]) ? 1 : intval($_REQUEST[page]);
// limit elementów na stronie
$limit = empty($_REQUEST[params][limit]) ? 20 : intval($_REQUEST[params][limit]);
// offset
$offset = ($page-1) * $limit;
// sortowanie
$orderBy = $wwwCatalog->getOrderBy($_REQUEST['order_by'],$_REQUEST['order_type']);
$out[orderType][$_REQUEST['order_by']] = intval($_REQUEST['order_type']);
$out[orderTypeReversed][$_REQUEST['order_by']] = $wwwCatalog->reverseOrderType($_REQUEST['order_type']);

// tworzy tablice z kryteriami do wyszukiwania
$criteria = array();
$criteria[show] = $show;


// grupy zaznaczone w filtracji
if(isset($_REQUEST[params])) {
	// pobiera z sesji gdy korzystamy ze stronnicowania
	$filteredGroups =  $_SESSION['www_catalog_filtered_groups'];
}
else {
    $filteredGroups = $_REQUEST['allow_menu_access'];	
    $_SESSION['www_catalog_filtered_groups'] = $filteredGroups;
}


if(count($filteredGroups) > 0) {
	$criteria['filteredGroups'] = $filteredGroups;
}

    
$allRowsNum = count($wwwCatalog->getList($criteria,null,null,$orderBy));
$Tab = $wwwCatalog->getList($criteria,$limit, $offset,$orderBy);


$Stats =  array(
	$T['form_count'] => count($Tab)
);

// pobiera listę grup
$groupsList = $wwwCatalog->getGroupList();
$generatedGroupList = $wwwCatalog->fetchGroups($groupsList,$filteredGroups);

// tworzy obiekt do paginacji
include_once '../kernel/Paging.php';
$paging = new Paging($allRowsNum, $limit, $page,$linkUrl);



require_once('tpl/header.html.php');
require_once('tpl/index_www_catalog.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
