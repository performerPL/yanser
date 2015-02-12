<?php
define('_APP', 1);

require_once '_config.php';

require_once '../lib/_system.php'; //biblioteka z narzędziami potrzebnymi zawsze
require_once '../lib/_gui.php'; //interfejs użytkownika
require_once '../lib/_db.php'; // baza danych
require_once '../lib/_sec.php'; //bezpieczeństow i sesje
require_once '../lib/_mail.php'; //wysyłanie mail
// jeśli ma być używany memcache
if(USE_MEMCACHE == 1) {
require_once '../kernel/Classes/tools/Memcached.php';
}

require_once 'lang/' . ADMIN_LANG . '.php';

if (ADMIN_HTTPS && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on')) {
  _redirect(ADMIN_PATH);
}

//rozpocznij sesje
session_start();

//polacz sie z baza danych
$_APP_DB = @mysql_connect($SQLServer, $SQLUser, $SQLPass) or die('No database connection.');
mysql_select_db($SQLDatabase);
mysql_query('SET NAMES ' . $SQLNames);

