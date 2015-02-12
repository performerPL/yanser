<?php
require_once '_header.php';
require_once '../lib/www_user.php';
_sec_authorise(ACCESS_MIN_ADMIN);

$Message = '';

$cmd = $_POST['cmd'];

switch ($cmd) {
  case 'updateactive':
    $users = $_POST['actives'];
    if (!is_array($users)) {
      $users = array();
    }

// wywaliłem usuwanie całości, bo nie działa to poprawnie przy stronnicowaniu, a wiecej z tego szkody niż pożytku    
//    _db_query('UPDATE ' . DB_PREFIX . 'www_user SET wu_active = 0');
    foreach ($users as $k => $v) {
      if ($v == 1) {
        _db_query('UPDATE ' . DB_PREFIX . 'www_user SET wu_active = 1 WHERE wu_id=' . _db_int($k));
      }
    }
    $Message = 'Aktywacja użytkowników została zmieniona';
    break;
}

// tworzy obiekt pomocniczy
include_once 'class/UserWWW.class.php';
$UserWWW = new UserWWW();

// rodzaj filtrowania  - domyślnie = 0 (pokazuj wszystkie)
$show = !isset($_REQUEST[params][show]) ? 0 : intval($_REQUEST[params][show]);
// numer strony - domyślnie = 1
$page = empty($_REQUEST[page]) ? 1 : intval($_REQUEST[page]);
// limit elementów na stronie
$limit = empty($_REQUEST[params][limit]) ? 20 : intval($_REQUEST[params][limit]);
// offset
$offset = ($page-1) * $limit;
// sortowanie
$orderBy = $UserWWW->getOrderBy($_REQUEST['order_by'],$_REQUEST['order_type']);
$out[orderType][$_REQUEST['order_by']] = intval($_REQUEST['order_type']);
$out[orderTypeReversed][$_REQUEST['order_by']] = $UserWWW->reverseOrderType($_REQUEST['order_type']);

// tworzy tablice z kryteriami do wyszukiwania
$criteria = array();
$criteria[show] = $show;
$allRowsNum = count($UserWWW->getUserList($criteria,null,null,$orderBy));
$Tab = $UserWWW->getUserList($criteria,$limit, $offset,$orderBy);


// tworzy obiekt do paginacji
include_once '../kernel/Paging.php';
$paging = new Paging($allRowsNum, $limit, $page,$linkUrl);


$Stats =  array(
  $T['user_count'] => count($Tab)
);

require_once 'tpl/header.html.php';
require_once 'tpl/index_www_user.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
