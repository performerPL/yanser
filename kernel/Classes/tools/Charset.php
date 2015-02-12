<?php

/**
 * Klasa pomocnicza do konwersji kodowania znaków
 * 
 * @author Darek Skrzypczak <kontakt@app4you.pl>
 *
 */
class Charset {
	
	public static $UTF8_TO_ISO88592 = 1;
  	public static $ISO88592_TO_UTF8 = 2;
  	public static $WIN1250_TO_UTF8 = 3;
  	public static $UTF8_TO_WIN1250 = 4;
  	public static $ISO88592_TO_WIN1250 = 5;
  	public static $WIN1250_TO_ISO88592 = 6;
	
	/**
	 * Konwertuje podany string.
	 */
	public static function convert($string, $type) {
 
    $win2utf = array(
      "\xb9" => "\xc4\x85", "\xa5" => "\xc4\x84",
      "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
      "\xea" => "\xc4\x99", "\xca" => "\xc4\x98",
      "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
      "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93",
      "\x9c" => "\xc5\x9b", "\x8c" => "\xc5\x9a",
      "\xbf" => "\xc5\xbc", "\x8f" => "\xc5\xbb",
      "\x9f" => "\xc5\xba", "\xaf" => "\xc5\xb9",
      "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83"
    );
    $iso2utf = array(
      "\xb1" => "\xc4\x85", "\xa1" => "\xc4\x84",
      "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
      "\xea" => "\xc4\x99", "\xca" => "\xc4\x98",
      "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
      "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93",
      "\xb6" => "\xc5\x9b", "\xa6" => "\xc5\x9a",
      "\xbc" => "\xc5\xba", "\xac" => "\xc5\xb9",
      "\xbf" => "\xc5\xbc", "\xaf" => "\xc5\xbb",
      "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83"
    );
 
    if ($type == self :: $ISO88592_TO_UTF8)
      return strtr($string, $iso2utf);
    if ($type == self :: $UTF8_TO_ISO88592)
      return strtr($string, array_flip($iso2utf));
    if ($type == self :: $WIN1250_TO_UTF8)
      return strtr($string, $win2utf);
    if ($type == self :: $UTF8_TO_WIN1250)
      return strtr($string, array_flip($win2utf));
    if ($type == self :: $ISO88592_TO_WIN1250)
      return strtr($string, "\xa1\xa6\xac\xb1\xb6\xbc",
        "\xa5\x8c\x8f\xb9\x9c\x9f");
    if ($type == self :: $WIN1250_TO_ISO88592)
      return strtr($string, "\xa5\x8c\x8f\xb9\x9c\x9f",
        "\xa1\xa6\xac\xb1\xb6\xbc");
		else
			return $string;				
	}
	
	/**
	 * Konwertuje tablice na podane kodowanie.
	 */
	public static function convertArray($arr,$type){
		// tablica po konwersji
		$ra = array();
		foreach($arr as $k => $val) {
			if(!is_numeric($val)) 
				$val = self :: $convert($val,$type);
			$ra[$k] = $val;
		}		
		return $ra;
	}
}
	 
?>
