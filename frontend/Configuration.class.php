<?php
if (!defined('_APP')) {
  exit;
}

require_once 'lib/config.php';
require_once 'lib/config_value.php';

class Configuration
{
  private $Conf;

  function __construct()
  {
    $this->Conf = config_value_tree();
  }

  function get($name)
  {
    return isset($this->Conf[$name]) ? $this->Conf[$name] : false;
  }
}
