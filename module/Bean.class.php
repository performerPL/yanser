<?php

/**
 * Klasa po której dziedziczą moduły. 
 * Zawiera ogólne dla wszsytkich modułów funkcje.
 *
 *
 * @author Darek Skrzypczak <kontakt@app4you.pl>
 *
 */
class Mod_Bean {

	/**
	 * @var object obiekt Smarty, do użytku w klasach potomnych
	 */
	protected $smarty;

	/**
	 * @var string scieżka względna
	 */
	protected $includePath = '';

	/**
	 * @var array tablica mapująca dla sortowania
	 */
	protected $orderType = array(
	0 => "ASC",
	1 => "DESC"
	);

	/**
	 * Konstruktor klasy Bean
	 * Tworzy obiekty pomocnicze.
	 *
	 */
	public function __construct() {
		$this->initSmarty();
	}

	/**
	 * Inicjuje obiekt smarty.
	 * Domyśnie katalog z szablonami znajduje sie w  templates/smarty...
	 *
	 *
	 * @return unknown_type
	 */
	private function initSmarty() {
		require_once $this->includePath . 'kernel/Classes/Smarty/Class.Smarty.main.php';

		$this->smarty = new Smarty();

		$this->smarty->template_dir = $this->includePath . 'templates/smarty';
		$this->smarty->compile_dir  = $this->includePath . 'cache/template';
		$this->smarty->cache_dir    = $this->includePath . 'cache/template';
	}

	/**
	 * Zwraca tablicę z danymi modułu (article_content).
	 * Nadpisuje dane modułu danymi z tablicy $module.
	 *
	 * @param $moduleId Id modułu
	 * @param $module Tablica z ktorej wartości maja być nadpisane
	 * @return array Połaczona tablica z danymi.
	 */
	public function getModuleContent($moduleId,$module = array()) {
		include_once $this->includePath . 'lib/article.php';
		// pobiera dane modułu
		$moduleData = article_mod_get($moduleId);
		// gdy $module to tablica, dołącza z niej dane
		if(is_array($module))
		$moduleData = array_merge($moduleData,$module);
		return $moduleData;
	}


	/**
	 * Mapuje numer na nazwe pola w bazie.
	 *
	 * @return unknown_type
	 */
	protected function mapCol2DbCol($colNumber) {
		return $this->colList[$colNumber][dbCol];
	}

	/**
	 * Zwraca typ sortowania.
	 *
	 * @param $colNumber Numer kolumny
	 * @param $orderType Typ sortowania
	 *
	 * @return string|null
	 */
	public function getOrderBy($colNumber,$orderType) {
		// sprawdza czy kolumna do posortowania jest prawidłowa
		if(!empty($this->colList[$colNumber][dbCol]))
		return $this->colList[$colNumber][dbCol] . " " . $this->orderType[$orderType];
		else
		return $this->colList["default"];
	}

	/**
	 * Zwraca przeciwny typ do sortowania w bazie.
	 *
	 * @param $orderTypeNum
	 * @return int 1|0
	 */
	public function reverseOrderType($orderTypeNum) {
		return empty($orderTypeNum) ? 1 : 0;
	}

	/**
	 * Pobranie tablicy w formie prostej listy klucz => wartość
	 *
	 * @param array $array Tablica danych
	 * @param string $key optional  Nazwa kolumny danych będąca kluczem w tabeli wynikowej.
	 * @param string $value optional  Nazwa kolumny danych będąca wartością w tabeli wynikowej.
	 * @return array Tablica w postaci klucz => wartość
	 */
	public function getSimpleArray($array, $key="id", $value="name")
	{
		$newArray = array();
		if(is_array($array))
		{
			foreach($array as $k => $v)
			{
				// przypisuje nowy wiersz w tablicy klucz => wartość
				$newArray[$v["$key"]] = $v["$value"];
			}
		}

		return $newArray;
	}


	protected function sendRegisterUserData($user) {

		$mail = $this->getMailInstance();

		$subject = 'Rejestracja na portalu rolnictwo.agro.pl -';
		if($user['wu_newsletter'] == 1) {
			$subject .= ' newsletter';
		}
		else {
			$subject .= ' użytkownik';
		}

		$mail->setSubject($subject);
		$mail->assignTemplateText('../_mail/register_user_data.txt');
		$mail->replace('USER', $user);
		// pobiera listę grup
		include_once 'lib/www_user.php';
		$mail->replace('GROUPS', $this->getSimpleArray(www_user_group_list(0),"wug_id","wug_name"));

		global $rootMailList;

		// dodaje adresy administratorów 
		if(!empty($rootMailList)) {
			foreach($rootMailList as $rootMail) {
				$mail->add($rootMail);
			}
		}

		// wysyła maila
		$mail->send();

	}

	public function getSmarty() {
		return $this->smarty;
	}

	public function getMailInstance() {

		set_include_path($this->includePath . PATH_SEPARATOR . get_include_path());
		set_include_path($this->includePath.'kernel/PEAR/' . PATH_SEPARATOR . get_include_path());
		set_include_path($this->includePath.'kernel/' . PATH_SEPARATOR . get_include_path());
		require_once $this->includePath.'kernel/Classes/Smarty/Class.Smarty.main.php';
		require_once $this->includePath.'kernel/Classes/vCheck/Class.vCheck.main.php';
		require_once $this->includePath.'kernel/Classes/vConvert/Class.vConvert.main.php';
		require_once $this->includePath.'kernel/Classes/vFile/Class.vFile.main.php';
		require_once $this->includePath.'kernel/Classes/VMail/Class.VMail.main.php';
		include_once $this->includePath.'frontend/Site.class.php';

		return new VMail($this->getSmarty());
	}


}