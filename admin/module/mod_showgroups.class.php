<?php
require_once '../lib/promotion.php';

class mod_showgroups
{
  function update($tab)
  {
    $R = array(
      'module_id' => _db_int($tab['module_id']),
      'style' => _db_int($tab['style']),
      'grupa' => _db_int($tab['grupa']),
      'wyniki' => _db_int($tab['wyniki']),
      'strony' => _db_int($tab['strony']),
      'show_title' => _db_int($tab['show_title']),
      'show_date' => _db_int($tab['show_date']),
      'show_date_mod' => _db_int($tab['show_date_mod']),
      'show_zajawka' => _db_int($tab['show_zajawka']),
      'show_icon' => _db_int($tab['show_icon']),
      'pokazuj' => _db_int($tab['pokazuj']),
      'show_author'=>_db_int($tab['show_author']),
    );
    return _db_replace('mod_showgroups', $R);
  }
  
  function remove($id)
  {
    return _db_delete('mod_showgroups', 'module_id='.intval($id), 1);
  }

  function validate($tab, $T)
  {
    return true;
  }

  function get($id)
  {
    $res = _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_showgroups` WHERE module_id='.intval($id).' LIMIT 1');
    $GRUPY = array();
    $PR = promotion_list();
    foreach ($PR as $k => $V) {
      $GRUPY[$V['promotion_id']] = $V['name'];
    }
    $res['GRUPY'] = $GRUPY;
    return $res;
  }

  function front($module, $Item)
  {
    switch ($module['style']) {
      case 0:
      case 1:
      case 2:
        echo '<div class="space"></div>';
        echo '<object width="' . $module['varx'] . '" height="' . $module['vary'] . '"><param name="movie" value="http://www.youtube.com/v/' . $module['ident'] . '&hl=pl&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $module['ident'] . '&hl=pl&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $module['varx'] . '" height="' . $module['vary'] . '"></embed></object>';
        break;
    }
  }
}