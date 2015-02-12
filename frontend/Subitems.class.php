<?php
if (!defined('_APP')) {
  exit;
}

class Subitems extends ItemTree
{
  function __construct($id, $level=1)
  {
    parent::__construct('', $id, $level, 'subitems');
  }
}
