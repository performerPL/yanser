<?php
require_once '_header.php';
require_once '../lib/newsletter.php';
require_once '../lib/www_user.php';
_sec_authorise(ACCESS_MIN_ADMIN);

// tworzy obiekt pomocniczy
include_once 'class/Newsletter.class.php';
$Newsletter = new Newsletter();

// dodatkowa akcja
// wysyłka testowa
if($_REQUEST['action'] == 'send_test') {
	$Newsletter->sendTest($_REQUEST['newsletter_id']);
	_redirect('index_newsletter.php');
}
// wysyłka ręczna do klientów
else if($_REQUEST['action'] == 'send') {
    $Newsletter->send($_REQUEST['newsletter_id']);
    _redirect('index_newsletter.php');
}




// rodzaj filtrowania  - domyślnie = 0 (pokazuj wszystkie)
$show = !isset($_REQUEST[params][show]) ? 0 : intval($_REQUEST[params][show]);
// numer strony - domyślnie = 1
$page = empty($_REQUEST[page]) ? 1 : intval($_REQUEST[page]);
// limit elementów na stronie
$limit = empty($_REQUEST[params][limit]) ? 20 : intval($_REQUEST[params][limit]);
// offset
$offset = ($page-1) * $limit;
// sortowanie
$orderBy = $Newsletter->getOrderBy($_REQUEST['order_by'],$_REQUEST['order_type']);
$out[orderType][$_REQUEST['order_by']] = intval($_REQUEST['order_type']);
$out[orderTypeReversed][$_REQUEST['order_by']] = $Newsletter->reverseOrderType($_REQUEST['order_type']);

// tworzy tablice z kryteriami do wyszukiwania
$criteria = array();
$criteria[show] = $show;
$allRowsNum = count($Newsletter->getList($criteria,null,null,$orderBy));
$Tab = $Newsletter->getList($criteria,$limit, $offset,$orderBy);


// tworzy obiekt do paginacji
include_once '../kernel/Paging.php';
$paging = new Paging($allRowsNum, $limit, $page,$linkUrl);


$Stats =  array(
  $T['user_count'] => count($Tab)
);

// lista z grupami
$menuList = www_user_group_list(0);

require_once 'tpl/header.html.php';
require_once 'tpl/index_newsletter.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
