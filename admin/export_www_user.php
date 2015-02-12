<?php
require_once '_header.php';
require_once '../lib/www_user.php';
_sec_authorise(ACCESS_MIN_ADMIN);

switch ($_REQUEST['type']) {
	// exportuje do pliku excela - XLS
	case 'xls':
		set_include_path('../kernel/PEAR/' . PATH_SEPARATOR . get_include_path());
		//		include_once '../kernel/PEAR.php';
		include_once '../kernel/PEAR/Spreadsheet/Excel/Writer.php';
		include_once '../kernel/Excel.php';

		$excel = new Excel();

		// nagłówek tabeli
		$tableHeader = array(
		'wu_lastname' => 'Nazwisko',
		'wu_firstname'=> 'Imię',
        'wu_login' => 'Login',
        'wu_street' => 'Ulica',
        'wu_city' => 'Miasto',
        'wu_zipcode' => 'Kod pocztowy',
        'wu_mail' => 'Poczta',
        'wu_email' => 'Email',
        'wu_phone' => 'Telefon',
        'wu_fax' => 'Fax',
        'wu_cellphone' => 'Telefon komórkowy',
        'wu_gg' => 'Gadu-Gadu',
        'wu_skype' => 'Skype',
        'wu_site' => 'WWW',
        'wu_newsletter' => 'Newsletter',
        'wu_province' => 'Województwo',
        'wu_area' => 'Pow. gospodarstwa',
        'wu_plant_production' => 'Prod. roślinna',
        'wu_plant_production_desc' => 'jaka',
        'wu_animal_production' => 'Prod. zwierzęca',
        'wu_animal_production_desc' => 'jaka',
        'wu_technical_equipment' => 'Wyposażenie tech.',
		);

		// pobiera listę wszyskich userow
		$tableValues = www_user_list();

		// dodaje szczegółowe dane
		foreach($tableValues as &$row) {
			$row = www_user_get($row['wu_id']);
			
			// newsletter
			$row['wu_newsletter'] = ($row['wu_newsletter'] == 1) ? 'Tak' : 'Nie';
		}

		$tableFooter = array();

		$excel->createTable('Lista uzytkownikow www',$tableHeader,$tableValues,$tableFooter);

		$excel->sendXLS('Lista uzytkownikow');

		break;
}


