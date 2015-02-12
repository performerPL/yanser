<?php
//if(defined('mod_text.class')) die('aa');
define('mod_text.class', 1);

class mod_text
{
  function update($tab)
  {
    return _db_replace('mod_text', array('text_id'=>_db_int($tab['module_id']),'html_text'=>_db_string($tab['html_text'])));
  }

  function remove($id)
  {
    return _db_delete('mod_text', 'text_id='.intval($id),1);
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
    $data = $this->get($module['module_id']);
    $style = $module['module_style'];
    $styles = array(
    0	=> 'style="float: left; margin: 0 10px 10px 0;" ',
    1	=> 'style="float: right; margin: 0 0 10px 10px;" ',
    );
    if($module['show_module_title'])
    echo '<b>'.$module['module_name'].'</b><br />';
    echo '<div ' . $styles[$style] . '>';
    echo $data['html_text'];
    echo '</div>';
  }
}
