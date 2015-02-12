<?php
/**
 * Klasa do obsługi plików
 * @package File
 * @version 1.0
 * @author Jakub Roszkiewicz
 *
 */
class vFile
{
  private $file_type = 'text';
  private $file_handle;
  private $write = false;
  private $filepath = null;

  public function __construct($file, $file_type = 'text', $write = false, $new = false)
  {
    $this->open($file, $file_type, $write, $new);
  }

  public function open($file, $file_type = 'text', $write = false, $new = false)
  {
    if ($this->isOpen()) {
      $this->close();
    }
    if ($file_type == 'binary') {
      $opt = 'b';
      $this->file_type = 'b';
    } else {
      $opt = '';
      $this->file_type = 'text';
    }
    if ($write == true) {
      $opt .= 'w';
      $this->write = true;
    } else {
      $opt .= 'r';
      $this->write = false;
    }
    if ($new == true) {
      $opt .= '+';
    }
    $this->file_handle = fopen($file, $opt);
    if ($this->isOpen()) {
      $this->filepath = $file;
    }
  }

  public function isOpen()
  {
    if (is_resource($this->file_handle)) {
      return true;
    }
    return false;
  }

  public function add($text)
  {
    if ($this->write == true) {
      fputs($this->file_handle, $text);
      return true;
    }
    return false;
  }

  public function writeLine($text)
  {
    if ($this->write == true) {
      fputs($this->file_handle, $text . "\n");
      return true;
    }
    return false;
  }

  public function readAll()
  {
    return @fread($this->file_handle, filesize($this->filepath));
  }

  public function fetchAll($path)
  {
    $file = new File($path);
    $body = $file->ReadAll();
    $file->close();
    unset($file);
    return $body;
  }

  public function writeAll($text)
  {
    return fwrite($this->file_handle, $text);
  }

  public function save()
  {
    if ($this->write == true) {
      fclose($this->file_handle);
      return true;
    }
    return false;
  }

  public function close()
  {
    fclose($this->file_handle);
  }

  public static function writeArray($file, $ARRAY, $name = null)
  {
    if (is_null($name)) {
      $name = 'ARRAY';
    }
    $handle = new vFile($file, 'text', true, true);
    $ciag = "<?php \n";
    $ciag .= '$'.$name.' = array(';
    $ciag .= "\n";
    foreach ($ARRAY as $klucz => $kategoria) {
      $ciag .= "\n {$klucz} => array(\n";
      foreach ($kategoria as $kk => $kl) {
        $ciag .= " '{$kk}' => '{$kl}',\n";
      }
      $ciag .= "\n),\n";
    }
    $ciag .= "\n);\n?>";
    $handle->writeAll($ciag);
    $handle->close();
    return true;
  }
}