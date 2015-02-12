<?php

require_once 'header.php';
header('Content-Type: text/xml');
$lang_ver = $Site->getLanguage()->getCode();

if ($lang_ver != '')  {
  $Site->setLanguage($lang_ver);
}

$type = $_REQUEST[type];
 $obj = new Menu($type, 0, 1, 'map',false);

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "\r\n";
?>
  <urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
<?php
$obj->generateGoogleMap();
?>   
  </urlset>