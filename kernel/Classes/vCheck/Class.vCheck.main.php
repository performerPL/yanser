<?php
/**
 * Klasa odpowiadająca za walidację danych
 * @package Check
 * @access public
 * @version 1.1
 * @author Jakub Roszkiewicz
 */
class vCheck
{

  /**
   * funkcja odpowiadająca za sprawdzanie poprawności NIP
   * @param string $nip
   * @return boolean
   */
  public function is_nip($nip)
  {
    $nip = str_replace('-', '', $nip);
    $liczba = 657234567;
    $ostatnia = substr($nip, 9, 9);

    $suma = 0;

    for ($i = 0; $i <= 8; $i++) {
      $suma = $suma + (substr($nip, $i, 1) * substr($liczba, $i, 1));
    }
    $reszta = $suma % 11;
    if ($reszta == 10) {
      $reszta = 0;
    }
    if ($reszta == $ostatnia) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Funkcja sprawdzająca poprawność REGON
   * @param string $regon
   * @return boolean
   */
  public function is_regon($regon)
  {
    $liczba = 89234567;
    $ostatnia = substr($regon, 8, 8);

    for ($i = 0; $i <= 7; $i++) {
      $suma = $suma + (substr($regon, $i, 1) * substr($liczba, $i, 1));
    }
    $reszta = $suma % 11;
    if ($reszta == 10) {
      $reszta = 0;
    }
    if ($reszta == $ostatnia) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * funkcja sprawdzają poprawność PESEL
   * @param string $pesel
   * @return goolean
   */
  public function is_pesel($pesel)
  {
    $liczba = 1379137913;
    $ostatnia = substr($nip, 10, 10);

    for ($i = 0; $i <= 9; $i++) {
      $suma = $suma + (substr($pesel, $i, 1) * substr($liczba, $i, 1));
    }
    $reszta = $suma % 10;
    $reszta = 10 - $reszta;
    if ($reszta == 10) {
      $reszta = 0;
    }
    if ($reszta == $ostatnia) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * funkcja walidująca numer komórkowy
   * @param string $gsm (numer komórkowy, prawidłowy format to +XX.XXXXXXXXX)
   * @return boolean
   */
  public function is_gsm($gsm)
  {
    if (strlen($gsm) != 13) {
      return false;
    }
    $wzor_match = "/\+(\d{2})\.(\d{9})/";
    if (!preg_match($wzor_match, $gsm)) {
      return false;
    }
    return true;
  }

  public function is_tel($tel)
  {
    $wzor_match = "/^[\.0-9\-\+]+$/";
    if (!preg_match($wzor_match, $tel)) {
      return false;
    }
    return true;

  }

  /**
   * funkcja sprawdzajaca poprawność adresu email
   * @param string $value adres email
   * @return boolean
   */
  public function is_email($value)
  {
    return (!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', $value)) ? false : true;
  }

  /**
   * funkcja sprawdzająca, czy podana zmienna jest ciągiem URL
   * @param string $uri
   * @return boolean
   */
  public function is_uri($uri)
  {
    return (bool) preg_match(';^http\:\/\/[a-z0-9-]+.([a-z0-9-]+.)?[a-z]+;i', $uri);
  }

  /**
   * funkcja sprawdzająca poprawność kodu pocztowego
   * @param string $value
   * @return boolean
   */
  public function is_zcode($value)
  {
    if (!preg_match('/^\d{2}-\d{3}$/', $value)) {
      return false;
    }
    return true;
  }

  /**
   * funkcja sprawdzaja, czy zmienna jest stringiem
   * @param string $value
   * @return boolean
   */
  public function is_string($value)
  {
    if (!preg_match('/^[a-zA-Z\-_]+$/', Convert::ToAscii($value))) {
      return false;
    }
    return true;
  }

  /**
   * funkcja sprawdzająca, czy zmienna jest w formacie daty
   * @param string $date
   * @return boolean
   */
  public function is_date($date)
  {
    return (bool) preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date);
  }

  /**
   * sprawdzanie, czy zmienna jest wartością integer
   * @param int $int
   * @return boolean
   */
  public function is_integer($int)
  {
    if ($int===0) {
      return true;
    }
    $tmp = (int) $int;
    if ($tmp>0) {
      return true;
    }
    return false;
  }

  /**
   * funkcja sprawdzająca typ obrazu na podstawie podanego mime
   * @param string $type
   * @return string rozszerzenie lub false
   */
  public function is_image_typ($type)
  {
    $file_types = array ('application/x-shockwave-flash' => 'swf','image/pjpeg' => 'jpg', 'image/jpeg' => 'jpg', 'image/jpeg' => 'jpeg', 'image/gif' => 'gif', 'image/X-PNG' => 'png', 'image/PNG' => 'png', 'image/png' => 'png', 'image/x-png' => 'png', 'image/JPG' => 'jpg', 'image/GIF' => 'gif', 'image/bmp' => 'bmp', 'image/bmp' => 'BMP');

    if (!array_key_exists($type, $file_types)) {
      return false;
    } else {
      return $file_types[$type];
    }
  }

  /**
   * funkcja ktora sprawdza, czy podany plik jest poprawnego rozszerzenia
   * @param $filename nazwa pliku
   * @param $array_extension array z lista rozszerzen
   * @return boolean
   */
  public function is_allowed_extension($filename, $array_extension)
  {
    if (is_array($array_extension)) {
      foreach ($array_extension as $ex) {
        $tmpsize = strlen('.' . $ex);
        if (substr($filename, strlen($filename) - $tmpsize, $tmpsize) == '.' . $ex) {
          return true;
          break;
        }
      }
    }
    return false;
  }

}