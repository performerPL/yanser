<?php
require_once '_header.php';
require_once '../lib/www_user.php';
_sec_authorise(ACCESS_MIN_ADMIN);

$Message = '';

$Tab = www_user_get_history();

require_once 'tpl/header.html.php';
require_once 'tpl/index_www_history.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
