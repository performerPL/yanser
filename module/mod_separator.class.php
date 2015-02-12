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
        echo '<div class="width_site width_'.$module['module_id'].' width_separator"><div class="inside_content">';
        echo '<div class="space"></div>';
        echo '</div></div>';
        break;
      
      case 1:  /*BOX START <div>*/
         echo '<div class="width_site width_'.$module['module_id'].' "><div class="inside_content">';
        break;
        
      case 2: /*BOX STOP </div>*/
        echo '<div class="space"></div>';
        echo '</div></div>';
        break;
      case 3:
      echo '<div class="width_site width_'.$module['module_id'].' "><div class="inside_content">';
        echo '<div class="mod_separator3"></div>';
        echo '</div></div>';
        break;
              case 4:
              echo '<div class="width_site width_'.$module['module_id'].' width_separator"><div class="inside_content">';
        echo '<div class="mod_separator4"></div>';
        echo '</div></div>';
        break;
              case 5:
              echo '<div class="width_site width_'.$module['module_id'].' width_separator"><div class="inside_content">';
        echo '<div class="mod_separator5"></div>';
        echo '</div></div>';
        break;
              case 6:
              echo '<div class="width_site width_'.$module['module_id'].' width_separator"><div class="inside_content">';
        echo '<div class="mod_separator6"></div>';
        echo '</div></div>';
        break;
              case 7:
              echo '<div class="width_site width_'.$module['module_id'].' width_separator"><div class="inside_content">';
        echo '<div class="mod_separator7"></div>';
        echo '</div></div>';
        break;
              case 8:
              echo '<div class="width_site width_'.$module['module_id'].' width_separator"><div class="inside_content">';
        echo '<div class="mod_separator8"></div>';
        echo '</div></div>';
        break;
              case 9:
              echo '<div class="width_site width_'.$module['module_id'].' width_separator"><div class="inside_content">';
        echo '<div class="mod_separator9"></div>';
        echo '</div></div>';
        break;
    }
  }
}
