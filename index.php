<?php
// gdy ustawiony parametr ajax=1 włączamy buforowanie
if($_REQUEST[ajax] == 1)
ob_start();
require_once 'header.php';


//$addr = explode('/', $_SERVER['PATH_INFO']);
$addr = explode('/', $_SERVER['REQUEST_URI']);
//print_r($addr);
$lang_ver = '';
$start_adr = 1;
if ($addr[1] != $Site->getLanguage()->getCode() && is_object($Site->getLanguage($addr[1]))) {
	// jakas wersja jez.
	$lang_ver = $addr[1];
	$start_adr = 2;
} else {
	$lang_ver = $Site->getLanguage()->getCode();
}

define('LANG',$lang_ver);
if ($lang_ver != '')  {
	$Site->setLanguage($lang_ver);
}

$uf_url = intval($Config->get('USER_FRIENDLY_URL'));
$end_adr = $start_adr + $uf_url;
$path = array();

$_m_name = 'start';

if (!empty($DOMAINS_START)) {
	foreach ($DOMAINS_START as $k => $V) {
		if (strpos($_SERVER['HTTP_HOST'], $k) > 0) {
			$_m_name = $V;
		}
	}
} 


//pobiera i ustawia strone startowa   
$SET = get_item_start($menuItem);

//print_r ($SET); //zwraca ID strony startowej i Nazwe strony startowej

if (!is_null($SET['item_id'])) {
	$item_id = $SET['item_id'];
	$page_name = $SET['item_name'];
}

if (!$item_id) { echo 'ustaw stronę startową  w panelu!'; exit; } // zabezpiecza przed przekierowaniami gdy nie jest zdefiniowana strona startopa!


$page = 0;
if ($addr[$end_adr] != '') {
	$sub_addr = explode(',', $addr[$end_adr]);
	if (intval($sub_addr[0]) > 0) {
		$item_id = intval($sub_addr[0]);
	}
	if (intval($sub_addr[1]) > 0) {
		$page = intval($sub_addr[1]);
	}
	if ($sub_addr[2] != '') {
		$page_name = $sub_addr[2];
	}
}



$Item = new Item($item_id, false, $page, $page_name); //pobiera item, artykuł, historę, subitemy

if ($Item->getMenuID() > 0){
	$menu_code = get_menu_code ($Item->getMenuID()); //pobiera kod menu dla biezacego Itemu
}

$menu_code=$menu_code[menu_code];
if (!$menu_code) { echo 'Sprawdz czy strona stratowa jest aktywna'; exit; } // zabezpiecza gdy mamy bledne nazwy menu w config lub strona startowa jest nieaktywna!


if (in_array($menu_code, $menuItem )) { //sprawdza czy biezacy kod menu jest taki sam jak kod menu z tablicy dla danej domeny. Jezeli sie nie to ustawia strone glowna danej domeny
    //echo "Znaleziono menu";
} else {
	_redirect(MAIN_DOMAIN);
}



require_once 'config/_redirect.php'; //realizuje przekierowania na prawidlowe adresy



/* -------------WERYFIKACJA UZYTKWONIKA ------------------*/
if (!empty($_GET['VERIFY'])) {
	require_once 'lib/www_user.php';
	www_user_verify($_GET['VERIFY']);
	// nadaje zmienna sesyjna
	$_SESSION[show_registered_info] = true;
	// przekierowuje na strone główną
    _redirect(MAIN_DOMAIN);
}
else if (!empty($_GET['UVERIFY'])) {
	_db_query("DELETE FROM " . DB_PREFIX . "www_user WHERE wu_key=" . _db_string($_GET['UVERIFY']));
	// nadaje zmienna sesyjna
	$_SESSION[show_unregistered_info] = true;
	// przekierowuje na strone główną
    _redirect(MAIN_DOMAIN);
}

/* -------------WERYFIKACJA UZYTKWONIKA ------------------*/



$template = ($Item->getID() > 0) ? $Site->getTemplate($Item->getTemplateID()) : $Site->getTemplate(-1);

//echo $template;


if (file_exists(TEMPLATE_DIR . '/' . $template . '/' . TEMPLATE_FILE_CTRL)) {
	require_once TEMPLATE_DIR . '/' . $template . '/' . TEMPLATE_FILE_CTRL;
}
else {
	echo 'Nieprawidlowy szablon projektu!';
	exit;
}

if (file_exists(TEMPLATE_DIR . '/' . $template . '/' . TEMPLATE_FILE_VIEW)) {
	require_once TEMPLATE_DIR . '/' . $template . '/' . TEMPLATE_FILE_VIEW;
}
