<?php
/**
 * Klasa pomocnicza do stronnicowania wyników na stronie.
 * 
 * @author Darek Skrzypczak <kontakt@app4you.pl>
 *
 */
class Paging {
	 
	/**
	 * @var int Offset dla paginacji
	 */
	private $offset;
	/**
	 * @var int Limit dla paginacji
	 */
	private $limit;
	/**
	 * @var int Liczba stron
	 */
	private $numPages;
	/**
	 * @var int Numer bieżącej strony
	 */
	private $page;
	/**
	 * @var int Numer następnej strony
	 */
	private $next;
	/**
	 * @var int Numer poprzedniej strony
	 */
	private $prev;
	/**
	 * @var string Znak oddzielający numerki
	 */
	private $sign = "|";
	/**
	 * @var string Url dla linku
	 */
	private $linkUrl;
	/**
	 * @var int Liczba wszystkich elementów
	 */
	private $numHits;
	private $uniqueId;

	/**
	 * Konstruktor klasy Paging.
	 * 
	 * @param $numHits Liczba wszsytkich wierszy
	 * @param $limit Limit wierszy na stronie
	 * @param $page Bieżaca strona
	 * @param $linkUrl Url dla linku 
	 * @param $actionJS  Akcja JS po kliknieciu - [domyślnie] pobiera parametry mające w nazwie params i dorzuca do URLa 
	 * @return unknown_type
	 */
	public function __construct($numHits, $limit, $page,$linkUrl) {
		$this->numHits  = $numHits;
		$limit    = max($limit, 1);
		$page     = $page;
		$numPages = ceil($numHits / $limit);

		$page = max($page, 1);
		$page = min($page, $numPages);
		if($page > 1) $this->prev = $page-1;
		if($page < $numPages) $this->next = $page+1;
		$offset = ($page - 1) * $limit;

		$this->offset = $offset;
		$this->limit = $limit;
		$this->numPages = $numPages;
		$this->page = $page;
		$this->linkUrl = $linkUrl;
		
		// tworzy unikalne id
		$this->generateUniqueId();
	}

	/**
	 * Domyślny getter.
	 * 
	 * @param $var
	 * @return unknown_type
	 */
	function __get($var) {
		return $this->$var;
	}
	
	
	/**
	 * Domyślny setter.
	 * 
	 * @param $var
	 * @param $value
	 * @return unknown_type
	 */
	function __set($var, $value) {
		$this->$var = $value;
	}
	
	/**
	 * Tworzy unikalne id dla paginacji.
	 * 
	 * @return unknown_type
	 */
	private function generateUniqueId() {
		$this->uniqueId = md5(uniqid(mt_rand(), true));
	}

}

?>