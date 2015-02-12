<?php
define('mod_text.class', 1);

/**
 * Klasa mod_text, jest to moduł tekstowy w cms.
 * Umożliwia wrzucenie na stronie dowolnych treści tekstowych w tym też kod html i JS
 *
 * @author Darek Skrzypczak <kontakt@app4you.pl>
 *
 */
require_once 'module/Bean.class.php';

class mod_text extends Mod_Bean
{
	 /* typ modułu */
	 private $moduleType = 1;
	 
	 /* tablica dostępnych stylów */
	 private $styles;
	 
	 /**
	  * Konstruktor modułu tekstowego.
	  * Domyślnie pobiera style z bazy.
	  * 
	  * @return unknown_type
	  */
	 public function __construct() {
	 	// inicjuje konstruktor klasy nadrzędnej
	 	parent :: __construct();
	 	// pobiera style
	 	$this->styles = $this->getStyles();
	 }
	 
	 /**
	  * Pobiera style dla modułu.
	  * 
	  * @return unknown_type
	  */
	 private function getStyles() {
	 	// tymczasowo definiowana tutaj. docelowo w osobnym pliku konfiguracyjnym
	 	return array(
	 	0   => 'mod_text mod_text_0 box box-1-1 ',
	 	1   => 'mod_text mod_text_1 box box-1-2 ',
		2   => 'mod_text mod_text_2 box box-1-3 ',
		3   => 'mod_text mod_text_3 box box-1-4 ',
		4   => 'mod_text mod_text_4 box box-1-6 ',
		5   => 'mod_text mod_text_5 box box-1-8 ',
		6   => 'mod_text mod_text_6 box box-2-3 ',
		7   => 'mod_text mod_text_7 box box-3-4 ',
		8   => 'mod_text mod_text_8 box ',
		9   => 'mod_text mod_text_9 box '
	 	);
	 	//return _db_get('SELECT * FROM `'.DB_PREFIX.'mod_styles` WHERE module_type=' . $this->moduleType);
	 }

	 /**
	 * Uaktualnia dane modułu w bazie.
	 *
	 * @param $tab
	 * @return unknown_type
	 */
	public function update($tab) {
		return _db_replace('mod_text', array('text_id'=>_db_int($tab['module_id']),'html_text'=>_db_string($tab['html_text'])));
	}

	/**
	 * Usuwa moduł tekstowy z bazy.
	 *
	 * @param $id
	 * @return unknown_type
	 */
	public function remove($id) {
		return _db_delete('mod_text', 'text_id='.intval($id),1);
	}

	/**
	 * Funkcja walidująca przesyłane dane.
	 *
	 * @param $tab
	 * @param $T
	 * @return unknown_type
	 */
	public function validate($tab, $T) {
		return true;
	}

	/**
	 * Pobiera dane z bazy modułu tekstowego.
	 *
	 * @param $id Id modułu
	 * @return array Zwraca tablicę asocjacyjną z wierszem z tabeli mod_text
	 */
	public function get($id) {
		return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_text` WHERE text_id='.intval($id).' LIMIT 1');
	}

	/**
	 * Kontroler wyświetlający dane modułu w przegladarce.
	 * Kod HTML znajduje się w szablonie Smarty "mod_text/text.html".
	 *
	 * @param $module
	 * @param $Item
	 * @return unknown_type
	 */
	public function front($module, $Item)
	{
		// uaktualnia dane o module (dla ustawień ręcznych)
		$module = $this->getModuleContent($module['module_id'],$module);
		
		// pobiera dane modułu z bazy
		$data = $this->get($module['module_id']);

		// styl przypisany do modułu
		$style = $module['module_style'];
		
		$module_id = 'mod_'.$module['module_id'];
		//$module_id = '100';
		
		

		
		
		$data_all = '<div class="'.$this->styles[$style].' '.$module_id.'" id="'.$module_id.'" ><div class="margin"><div class="inside">'.$data.'</div></div></div>';
		

		/*****************************/
		/** SMARTY **/
		/*****************************/
		$out = array();
		$out[data] = $data;
		$out[styles] = $this->styles;
		$out[currentStyle] = $this->styles[$style];
		$out[module] = $module;
		$out[module_id] = $module_id;
		
		$out[all] = $data_all;
		
		
		
		
		// załącza tablicę z parametrami
		$this->smarty->assign('out',$out);
		// wyświetla listę
		$this->smarty->display("mod_text/text.html");
	}

}
