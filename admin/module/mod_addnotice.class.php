<?php
define('mod_addnotice.class', 1);

class mod_addnotice
{
  function update($tab)
  {
    return _db_replace('mod_addnotice', array('module_id'=>_db_int($tab['module_id']),'style'=>_db_int($tab['style']), 'per_page' => _db_int($tab['per_page'])));
  }

  function remove($id)
  {
    return _db_delete('mod_addnotice', 'module_id='.intval($id), 1);
  }

  function validate($tab, $T)
  {
    return true;
  }

  function get($id)
  {
    return _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_addnotice` WHERE module_id='.intval($id).' LIMIT 1');
  }

  function front($module, $Item)
  {

  }
}
