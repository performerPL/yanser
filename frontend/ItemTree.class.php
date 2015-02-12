<?php  
if (!defined('_APP')) {
  exit;
}
require_once 'lib/menu.php';
require_once 'lib/item.php';

class ItemTree 
{
  protected
  $level=1,
  $parent_id=0,
  $name = '',
  $for= '',
  $data = array();
  
  function __construct($name, $parent=0, $level=1, $for='',$simpleTab = true) 
  {
    $this->level = $level;
    $this->parent_id = $parent;
    $this->name = $name;
    $this->for = $for;
    
    $res = array();
    $cur_level = 1;
    
    item_client_tree_level($name, $level, $cur_level, false, $res, $parent, $for,$simpleTab);
    
    $this->data = $res;
  }
  
  function countItems($parent=-1) 
  {
    if ($parent < 0) {
      $parent=$this->getParentID();
    }
    return $this->data[$parent]->getCount();
  }
  
  function getItems($parent=-1) 
  {
    if ($parent<0) {
      $parent = $this->getParentID();
    }
    if (is_object($this->data[$parent])) {
      return $this->data[$parent]->toArray();
    } else {
      return array();
    }
  }
  
  function getItemsAll()
  {
    $array = array();
    foreach ($this->data as $k => $V) {
      $array = array_merge($array, $V->toArray());
    }
    return $array;
  }
  
  function getParentID() 
  {
    return intval($this->parent_id);
  }
  
  function getLevel() 
  {
    return $this->level;
  }
}