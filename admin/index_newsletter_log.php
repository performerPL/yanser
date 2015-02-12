<?php
require_once '_header.php';
require_once '../lib/newsletter.php';
require_once '../lib/www_user.php';
_sec_authorise(ACCESS_MIN_ADMIN);


$Tab = newsletter_log_list($_REQUEST['newsletter_id']);

require_once 'tpl/header.html.php';
require_once 'tpl/index_newsletter_log.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
