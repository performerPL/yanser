<?php 


$URI_Array = explode(",",$_SERVER["REQUEST_URI"]);
$hostArray = explode(".",$_SERVER['HTTP_HOST']);
$LinkArray = explode(",",$Item->getMapLink());

$curr_urlname = $URI_Array[2]; // current URL
$corr_urlname = $LinkArray[2]; // correct url



// sprawdza czy w adresie znajduje siê WWW i czy zmienna POST jest pusta
if($hostArray[0] != "www" && $hostArray[0] != "localhost" && empty($_POST)) {
	// przekierowuje na strone z WWW pomija znak / w REQUEST URI
	_redirect(MAIN_DOMAIN . $_SERVER[REQUEST_URI], 301);
	//echo 'przekierowanie na'.MAIN_DOMAIN . $_SERVER[REQUEST_URI]; 
}

if ($URI_Array[1] == 'logout') {	// usuwa sesje usera
	unset($_SESSION['user_www_id']);
}




// wyj¹tek dla artyku³u ID=7 -wyszukiwaki bo w adresie przekazuje zmienne do wyszukania


		//jezeli adres URL strony nie zgadza sie z adresem w serwisie to przekierowanie 301 na prawidlowy adres 
		if (($curr_urlname != $corr_urlname) && ($URI_Array[0]!='/') ) {
			$correct_url = MAIN_DOMAIN.'/'.$Item->getMapLink();
			_redirect($correct_url,301);
		}


