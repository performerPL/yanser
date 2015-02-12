<?php
if (!defined('_APP')) {
  exit;
}

require_once 'lib/promotion.php';

class Group extends ItemList
{

	private
  $GROUPS = array();

  function __construct($name, $limit=0)
  {
    parent::__construct(promotion_get_list_item($name, $limit));
  }
	
	
  function fetchGroups($name)
  {
    $res = _db_get_one('SELECT promotion_id FROM ' . DB_PREFIX . 'promotion WHERE promotion_code = ' . _db_string($name) . ' LIMIT 1');
    $GROUPS = _db_get('SELECT name, value FROM ' . DB_PREFIX . 'menu_addons WHERE menu_id = ' . _db_int($res['menu_id']));
    foreach ($GROUPS as $k => $V) {
      $this->GROUPS[$V['name']] = $V['value'];
    }
  }

  /**
   * Pobranie zmiennej ustalonej w panelu administracyjnym.
   *
   * @param mixed $i Nazwa zdefiniowana w pierwszym polu w edycji paska menu w panelu admina.
   * @return mixed Zwraca wartoœæ przypisan¹ w drugim polu w edycji paska menu w panelu admina.
   */
  function getGroups($i)
  {
    if (!empty($this->GROUPS[$i])) {
      return $this->GROUPS[$i];
    }
    return null;
  }
	
}
