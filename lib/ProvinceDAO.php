<?php
/**
 * Klasa z danymi o województwach.
 */
class ProvinceDAO {
	
	private static $list = array(
		0 => "",
		1 => "Dolnośląskie",
		2 => "Kujawsko-pomorskie",
		3 => "Lubelskie",
		4 => "Lubuskie",
		5 => "Łódzkie",
		6 => "Małopolskie",
		7 => "Mazowieckie",
		8 => "Opolskie",
		9 => "Podkarpackie",
		10 => "Podlaskie",
		11 => "Pomorskie",
		12 => "Śląskie",
		13 => "Świętokrzystkie",
		14 => "Warmińsko-mazurskie",
		15 => "Wielkopolskie",
		16 => "Zachodniopomorskie"
	);
	
	public static function getList(){
		return self::$list;
	}
	
	/**
	 * Zwraca nazwe województwa po id.
	 */
	public static function getNameByID($selectedId) { 
		foreach(self :: $list as $provinceId => $province) {
			if($provinceId == $selectedId)
				return $province;
		}
		return "";
	}	
	

} 
?>
