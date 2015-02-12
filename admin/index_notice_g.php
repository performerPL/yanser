<?php
require_once '_header.php';
require_once '../lib/notice.php';
_sec_authorise(ACCESS_MIN_ADMIN);

$Message = '';
$Tab = notice_group_list((int) $_GET['PARENT']);

$cmd = $_POST['cmd'];

$Message = '';

$Tab = notice_group_list((int) $_GET['PARENT']);

$PATHWAY = notice_get_pathway((int) $_GET['PARENT']);

require_once 'tpl/header.html.php';
require_once 'tpl/index_notice_g.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
