<?php
require_once '_header.php';
require_once '../lib/notice.php';
_sec_authorise(ACCESS_MIN_ADMIN);

$Message = '';

$cmd = $_POST['cmd'];

switch ($cmd) {

}

// tworzy obiekt pomocniczy
include_once 'class/Notice.class.php';
$Notice = new Notice();

// id usera
$userId = empty($_REQUEST[user_id]) ? intval($_REQUEST[params][user_id]) : intval($_REQUEST[user_id]);
// id grupy
$groupId = empty($_REQUEST[group_id]) ? intval($_REQUEST[params][group_id]) : intval($_REQUEST[group_id]);

// rodzaj filtrowania  - domyślnie = 1 (aktywne)
$show = !isset($_REQUEST[params][show]) ? 1 : intval($_REQUEST[params][show]);
// numer strony - domyślnie = 1
$page = empty($_REQUEST[page]) ? 1 : intval($_REQUEST[page]);
// limit elementów na stronie
$limit = empty($_REQUEST[params][limit]) ? 20 : intval($_REQUEST[params][limit]);
// offset
$offset = ($page-1) * $limit;
// sortowanie
$orderBy = $Notice->getOrderBy($_REQUEST['order_by'],$_REQUEST['order_type']);
$out[orderType][$_REQUEST['order_by']] = intval($_REQUEST['order_type']);
$out[orderTypeReversed][$_REQUEST['order_by']] = $Notice->reverseOrderType($_REQUEST['order_type']);

// tworzy tablice z kryteriami do wyszukiwania
$criteria = array();
$criteria[show] = $show;

// lista ogloszen usera
if (!empty($userId)) {
	//  $Tab = notice_get_user_notices($_GET['user_id']);
	$criteria[userId] = $userId;
	$allRowsNum = count($Notice->getNoticeList($criteria,null,null,$orderBy));
	$Tab = $Notice->getNoticeList($criteria,$limit, $offset,$orderBy);
}
// lista ogloszen grupy
else {
	//  $Tab = notice_get_group_notices($_GET['group_id']);
	$criteria[groupId] = $groupId;
	$allRowsNum = count($Notice->getNoticeList($criteria,null,null,$orderBy));
	$Tab = $Notice->getNoticeList($criteria,$limit, $offset,$orderBy);
}


// tworzy obiekt do paginacji
include_once '../kernel/Paging.php';
$paging = new Paging($allRowsNum, $limit, $page,$linkUrl);




$Stats =  array(
  $T['user_count'] => count($Tab)
);

foreach ($Tab as $k => $V) {
  switch ($V['n_status']) {
    case 1:
      $Tab[$k]['status'] = 'aktywne';
      break;
      
    case 2:
      $Tab[$k]['status'] = 'przeterminowane';
      break;
      
    default:
      $Tab[$k]['status'] = 'nieaktywne';
      break;
  }
}

require_once 'tpl/header.html.php';
require_once 'tpl/index_notice_notice.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
