<?php 

session_start();

define('_APP', 1);

require_once 'config/_db.php';
require_once 'config/_admin.php';
require_once 'config/_app.php';
require_once 'lib/_system.php';
require_once 'lib/_db.php';
require_once 'frontend/_class.php';
require_once 'lib/gallery.php';
require_once 'lib/ftp.php';

// jeśli ma być używany memcache

if(USE_MEMCACHE == 1) {
require_once 'kernel/Classes/tools/Memcached.php';
}



$_APP_DB = @mysql_connect($SQLServer, $SQLUser, $SQLPass) or die('Cannot establish database connection.');
mysql_select_db($SQLDatabase);
mysql_query('SET NAMES ' . $SQLNames);

$Config = new Configuration;
$Site = new Site($Config);

$GL_CONF = config_value_tree();

