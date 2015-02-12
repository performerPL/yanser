<?php
if (!defined('_APP')) {
  exit;
}
if (defined('_CONFIG__MAIL.PHP')) {
  return;
}
define('_CONFIG__MAIL.PHP', 1);

define('MAIL_HOST', 'rolnictwo.agro.pl');
define('MAIL_LOGIN', 'automat@rolnictwo.agro.pl');
define('MAIL_PASSWORD', 'automatwww2009');
define('MAIL_FROM', 'automat@rolnictwo.agro.pl');

global $rootMailList;

// lista maili na które maja iść kopie rejestracji
$rootMailList = array(
'marcin@performer.pl',
'marcin@performer.pl'
);
