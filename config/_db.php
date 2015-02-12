<?php 

if (!defined('_APP')) {  exit; }
if (defined('_CONFIG__DB.PHP')) {  return; }
define('_CONFIG__DB.PHP', 1);



$SQLServer = 'localhost';
$SQLUser = 'perform_dbuser';
$SQLPass = 'DBuser#768';
$SQLNames = 'UTF8';
$SQLDatabase = 'perform_yanser';
define('DB_PREFIX', 'cms_');
// znacznik czy używać memcache
// UWAGA! jeżeli na serwer nie obsługuje memcached znacznik musi byc ustawiony na 0
define('USE_MEMCACHE',0);



