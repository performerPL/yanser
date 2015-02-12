<?php
define('mod_shownotices_tree.class', 1);

class mod_shownotices_tree
{
  function update($tab)
  {
    return _db_replace('mod_shownotices', array('module_id'=>_db_int($tab['module_id']),'style'=>_db_int($tab['style'])));
  }

  function remove($id)
  {
    return _db_delete('mod_shownotices', 'module_id='.intval($id), 1);
  }

  function validate($tab, $T)
  {
    return true;
  }

  function get($id)
  {
    return _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_shownotices` WHERE module_id='.intval($id).' LIMIT 1');
  }

  function front($module, $Item)
  {

  }
}
