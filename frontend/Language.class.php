<?php  
if (!defined('_APP')) {
  exit;
}

class Language
{
  private
  $name,
  $code,
  $img,
  $is_def;

  function __construct($lang, $code)
  {
    $this->name = $lang['LANG_NAME'];
    $this->img = $lang['LANG_FLAG'];
    $this->code = $code;
  }

  function setDefault($x)
  {
    return $this->is_def = $x;
  }

  function isDefualt()
  {
    return (boolean) $this->is_def;
  }

  function getCode()
  {
    return $code;
  }

}