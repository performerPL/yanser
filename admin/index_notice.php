<?php
require_once '_header.php';
require_once '../lib/www_user.php';
require_once '../lib/notice.php';
_sec_authorise(ACCESS_MIN_ADMIN);

$Message = '';

$cmd = $_POST['cmd'];

//$Tab = www_user_list();
// tworzy obiekt pomocniczy
include_once 'class/UserWWW.class.php';
$UserWWW = new UserWWW();

// pobiera listę tylko pełnych userów (bez newslettera)
$Tab = $UserWWW->getUserList(array("show" => 2));

$Stats = array(
  $T['user_count'] => count($Tab)
);

foreach ($Tab as $k => $V) {
  $Tab[$k]['count'] = notice_get_user_count($V['wu_id']);
}

require_once 'tpl/header.html.php';
require_once 'tpl/index_notice.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
