<?php
/**
 * Klasa konwertująca rózne rodzaje danych
 * @package Convert
 * @author Jakub Roszkiewicz
 * @version 1.0
 *
 */
class vConvert
{

  public function iso2utf($text)
  {
    $aiso2utf = array(
			"\xb1" => "\xc4\x85",
			"\xa1" => "\xc4\x84", 
			"\xe6" => "\xc4\x87", 
			"\xc6" => "\xc4\x86", 
			"\xea" => "\xc4\x99", 
			"\xca" => "\xc4\x98", 
			"\xb3" => "\xc5\x82", 
			"\xa3" => "\xc5\x81", 
			"\xf3" => "\xc3\xb3", 
			"\xd3" => "\xc3\x93", 
			"\xb6" => "\xc5\x9b", 
			"\xa6" => "\xc5\x9a", 
			"\xbc" => "\xc5\xba", 
			"\xac" => "\xc5\xb9", 
			"\xbf" => "\xc5\xbc", 
			"\xaf" => "\xc5\xbb", 
			"\xf1" => "\xc5\x84", 
			"\xd1" => "\xc5\x83"
			);
			return strtr($text, $aiso2utf);
  }

  public function utf2iso($text)
  {
    $aiso2utf = array(
			"\xb1" => "\xc4\x85",
			"\xa1" => "\xc4\x84", 
			"\xe6" => "\xc4\x87", 
			"\xc6" => "\xc4\x86", 
			"\xea" => "\xc4\x99", 
			"\xca" => "\xc4\x98", 
			"\xb3" => "\xc5\x82", 
			"\xa3" => "\xc5\x81", 
			"\xf3" => "\xc3\xb3", 
			"\xd3" => "\xc3\x93", 
			"\xb6" => "\xc5\x9b", 
			"\xa6" => "\xc5\x9a", 
			"\xbc" => "\xc5\xba", 
			"\xac" => "\xc5\xb9", 
			"\xbf" => "\xc5\xbc", 
			"\xaf" => "\xc5\xbb", 
			"\xf1" => "\xc5\x84", 
			"\xd1" => "\xc5\x83"
			);
			return strtr($text, array_flip($aiso2utf));
  }

  public function cp2iso($text)
  {
    return strtr($text, "\xa5\x8c\x8f\xb9\x9c\x9f", "\xa1\xa6\xac\xb1\xb6\xbc");
  }

  public function iso2cp($text)
  {
    return strtr($text, "\xa1\xa6\xac\xb1\xb6\xbc", "\xa5\x8c\x8f\xb9\x9c\x9f");
  }

  public function cp2utf($text)
  {
    $awin2utf = array(
			"\xb9" => "\xc4\x85", 
			"\xa5" => "\xc4\x84", 
			"\xe6" => "\xc4\x87", 
			"\xc6" => "\xc4\x86", 
			"\xea" => "\xc4\x99", 
			"\xca" => "\xc4\x98", 
			"\xb3" => "\xc5\x82", 
			"\xa3" => "\xc5\x81", 
			"\xf3" => "\xc3\xb3", 
			"\xd3" => "\xc3\x93", 
			"\x9c" => "\xc5\x9b", 
			"\x8c" => "\xc5\x9a", 
			"\xbf" => "\xc5\xbc", 
			"\x8f" => "\xc5\xbb", 
			"\x9f" => "\xc5\xba", 
			"\xaf" => "\xc5\xb9", 
			"\xf1" => "\xc5\x84", 
			"\xd1" => "\xc5\x83"
			);
			return strtr($text, $awin2utf);
  }

  public function utf2cp($text)
  {
    $awin2utf = array(
			"\xb9" => "\xc4\x85", 
			"\xa5" => "\xc4\x84", 
			"\xe6" => "\xc4\x87", 
			"\xc6" => "\xc4\x86", 
			"\xea" => "\xc4\x99", 
			"\xca" => "\xc4\x98", 
			"\xb3" => "\xc5\x82", 
			"\xa3" => "\xc5\x81", 
			"\xf3" => "\xc3\xb3",
			"\xd3" => "\xc3\x93", 
			"\x9c" => "\xc5\x9b", 
			"\x8c" => "\xc5\x9a", 
			"\xbf" => "\xc5\xbc", 
			"\x8f" => "\xc5\xbb", 
			"\x9f" => "\xc5\xba", 
			"\xaf" => "\xc5\xb9", 
			"\xf1" => "\xc5\x84", 
			"\xd1" => "\xc5\x83"
			);
			return strtr($text, array_flip($awin2utf));
  }

  public function detectUTF8($string)
  {
    return preg_match('%(?:
       [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
       |\xE0[\xA0-\xBF][\x80-\xBF]              # excluding overlongs
       |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
       |\xED[\x80-\x9F][\x80-\xBF]              # excluding surrogates
       |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
       |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
       |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
       )+%xs', $string);
  }

  public function detectCharset($string)
  {
    if($this->detectUTF($string))
    return 1; elseif($this->detectISO($string))
    return 3; elseif($this->detectCP($string))
    return 2; else
    return 0;
  }

  public function toAscii($string)
  {
    $string = str_replace(array('!', '/'), '', $string);
    $string = str_replace(' ', '_', strtr(iconv("utf-8", 'cp1250', $string), "\xCA\xEA\xD3\xF3\xA3\xB3\x8C\x9C\xA5\xB9\xAF\xBF\x8F\x9F\xC6\xE6\xD1\xF1", "EeOoLlSsAaZzZzCcNn"));

    return $string;
  }

  /* przydatny bzdet zamieniający w wyswietlanych liczbach kropki na przecinki bo tak sie powinno podawać w polsce ceny */
  public function przecinek($liczba, $prz = true)
  {
    $l = sprintf("%01.2f", $liczba);
    if (!$prz) {
      return ($l);
    }
    return (ereg_replace("\.", ',', $l));
  }

}