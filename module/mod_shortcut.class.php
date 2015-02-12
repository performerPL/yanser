<?php

define('mod_shortcut.class', 1);

class mod_shortcut
{
  function update($tab)
  {
    return _db_replace('mod_shortcut', array('module_id'=>_db_int($tab['module_id']), 'styl'=>_db_int($tab['styl'])));
  }

  function remove($id)
  {
    return _db_delete('mod_shortcut', 'module_id='.intval($id), 1);
  }

  function validate($tab, $T)
  {
    return true;
  }

  function get($id)
  {
    return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_shortcut` WHERE module_id='.intval($id).' LIMIT 1');
  }

  function front($module, $Item)
  {
    echo '<div id="shortcut">' . $Item->getDescription() . '</div>';
  }
}

