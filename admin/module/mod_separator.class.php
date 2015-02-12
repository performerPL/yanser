<?php
define('mod_separator.class', 1);

class mod_separator
{
  function update($tab)
  {
    return _db_replace('mod_separator', array('module_id'=>_db_int($tab['module_id']),'style'=>_db_int($tab['style'])));
  }

  function remove($id)
  {
    return _db_delete('mod_separator', 'module_id='.intval($id), 1);
  }

  function validate($tab, $T)
  {
    return true;
  }

  function get($id)
  {
    return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_text` WHERE text_id='.intval($id).' LIMIT 1');
  }

  function front($module, $Item)
  {
    switch ($module['module_style']) {
      case 0:
        echo '<div class="space"></div>';
        break;
      
      case 1:
        echo '<div class="space"></div><hr>';
        break;
        
      case 2:
        echo '<div class="space"></div>';
        break;
    }
  }
}
