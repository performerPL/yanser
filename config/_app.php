<?php 
if (!defined('_APP')) {  exit; }
if (defined('_CONFIG__APP.PHP')) { return; }

define('_CONFIG__APP.PHP', 1);
define('SITE_PATH', '');
define('FILES_PATH', 'user_files/');
define('USE_MOD_REWRITE', 1);
define('INDEX_SCRIPT', 'index.php');
define('LICZNIK_ART_EXPIRE', 21600); // 6h
// globalny czas ważności ogłoszenia -60 dni
define('NOTICE_DURATION',60);
// lista botów
global $BOT_LIST;
$BOT_LIST = array( "Googlebot", "yahoo",  "alexa","crawler");


 if (($_SERVER['HTTP_HOST'] == 'yanser.performer.pl') || ($_SERVER['HTTP_HOST'] == 'www.yanser.performer.pl')) {
		define('MAIN_DOMAIN', 'http://www.yanser.performer.pl');
		$menuItem = array('menu_1', 'menu_2');
		foreach($menuItem as $row)
		{
			$domainItem[$row] == 'MAIN_DOMAIN';
		}
		// $domainItem['menu_1'] == 'MAIN_DOMAIN';
		// $domainItem['menu_2'] == 'MAIN_DOMAIN';	
		define('ALT_TEXT', ' Yanser Polska bielizna, rajstopy '); // domyslny tekst dla zdjec w znaczniku ALT
		define('TITLE_TEXT', ' Yanser Polska bielizna, rajstopy '); // domyslny tekst dla zdjec w znaczniku TITLE
 } else  if (($_SERVER['HTTP_HOST'] == 'performer.pl') || ($_SERVER['HTTP_HOST'] == 'www.performer.pl')) {
		define('MAIN_DOMAIN', 'http://www.performer.pl');
		$menuItem = array('performer_1_pl', 'performer_1_pl');
		foreach($menuItem as $row)
		{
			$domainItem[$row] == 'MAIN_DOMAIN';
		}
		// $domainItem['menu_1'] == 'MAIN_DOMAIN';
		// $domainItem['menu_2'] == 'MAIN_DOMAIN';	
		define('ALT_TEXT', ' Strony www, CMS, DTP, POS, aplikacje, Marcin Wojtkowiak, Poznań '); // domyslny tekst dla zdjec w znaczniku ALT
		define('TITLE_TEXT', ' Strony www, CMS, DTP, POS, aplikacje, Marcin Wojtkowiak, Poznań '); // domyslny tekst dla zdjec w znaczniku TITLE
 } else  if (($_SERVER['HTTP_HOST'] == 'olocca.performer.pl') || ($_SERVER['HTTP_HOST'] == 'www.olocca.performer.pl')) {
		define('MAIN_DOMAIN', 'http://www.olocca.performer.pl');
		$menuItem = array('menu_olocca_1', 'menu_olocca_2');
		$domainItem['menu_olocca_1'] == 'MAIN_DOMAIN';
		$domainItem['menu_olocca_2'] == 'MAIN_DOMAIN';	
		define('ALT_TEXT', 'O\'locca '); // domyslny tekst dla zdjec w znaczniku ALT
		define('TITLE_TEXT', ' O\'locca '); // domyslny tekst dla zdjec w znaczniku TITLE
 }
 


 //$domainItem['...'] == '.../'; //opcja przy wielu domenach