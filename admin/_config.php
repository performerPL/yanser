<?php 
if (!defined('_APP')) {
exit;
} 
if (defined('_ADMIN__CONFIG.PHP')) {
return;
} 
define('_ADMIN__CONFIG.PHP', 1);

require_once '../config/_db.php';
require_once '../config/_app.php';
require_once '../config/_path.php';
require_once '../config/_admin.php';

