<?php

class mod_youtube
{
  function update($tab)
  {
    $R = array(
      'module_id' => _db_int($tab['module_id']),
      'style' => _db_int($tab['style']),
      'ident' => _db_string($tab['ident']),
      'varx' => _db_int($tab['varx']),
      'vary' => _db_int($tab['vary']),
    );
    return _db_replace('mod_youtube', $R);
  }
  
  function remove($id)
  {
    return _db_delete('mod_youtube', 'module_id='.intval($id), 1);
  }

  function validate($tab, $T)
  {
    return true;
  }

  function get($id)
  {
    $res = _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_youtube` WHERE module_id='.intval($id).' LIMIT 1');
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