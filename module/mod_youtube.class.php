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
    $data = $this->get($module['module_id']);
		
		echo $module['style'];
		
		$moduleID = $module['module_id'];
		// pobiera styl
		$style = $module['module_style'];
		
    switch ($style) {
      case 0:
        echo '<div class="width_site width_'.$moduleID.'"><div class="inside_content">';
        echo '<div class="mod_youtube box  mod_youtube_0 mod_'.$moduleID.'" id="mod_'.$moduleID.'"><div class="margin"><div class="inside">';
        echo '<object width="' . $data['varx'] . '" height="' . $data['vary'] . '"><param name="movie" value="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $data['varx'] . '" height="' . $data['vary'] . '"></embed></object>';
        echo '</div></div></div></div></div>';
	break;
      case 1:
      echo '<div class="width_site width_'.$moduleID.'"><div class="inside_content">';
        echo '<div class="mod_youtube box  mod_youtube_1 mod_'.$moduleID.'" id="mod_'.$moduleID.'"><div class="margin"><div class="inside">';
        echo '<object width="' . $data['varx'] . '" height="' . $data['vary'] . '"><param name="movie" value="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $data['varx'] . '" height="' . $data['vary'] . '"></embed></object>';
        echo '</div></div></div></div></div>';
	break;
      case 2:
      echo '<div class="width_site width_'.$moduleID.'"><div class="inside_content">';
        echo '<div class="mod_youtube box  mod_youtube_2 mod_'.$moduleID.'" id="mod_'.$moduleID.'"><div class="margin"><div class="inside">';
        echo '<object width="' . $data['varx'] . '" height="' . $data['vary'] . '"><param name="movie" value="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $data['varx'] . '" height="' . $data['vary'] . '"></embed></object>';
        echo '</div></div></div></div></div>';
	break;
      case 3:
      echo '<div class="width_site width_'.$moduleID.'"><div class="inside_content">';
        echo '<div class="mod_youtube box  mod_youtube_3 mod_'.$moduleID.'" id="mod_'.$moduleID.'"><div class="margin"><div class="inside">';
        echo '<object width="' . $data['varx'] . '" height="' . $data['vary'] . '"><param name="movie" value="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $data['varx'] . '" height="' . $data['vary'] . '"></embed></object>';
        echo '</div></div></div></div></div>';
	break;
      case 4:
      echo '<div class="width_site width_'.$moduleID.'"><div class="inside_content">';
        echo '<div class="mod_youtube box  mod_youtube_4 mod_'.$moduleID.'" id="mod_'.$moduleID.'"><div class="margin"><div class="inside">';
        echo '<object width="' . $data['varx'] . '" height="' . $data['vary'] . '"><param name="movie" value="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $data['varx'] . '" height="' . $data['vary'] . '"></embed></object>';
        echo '</div></div></div></div></div>';
	break;
      case 5:
      echo '<div class="width_site width_'.$moduleID.'"><div class="inside_content">';
        echo '<div class="mod_youtube box  mod_youtube_5 mod_'.$moduleID.'" id="mod_'.$moduleID.'"><div class="margin"><div class="inside">';
        echo '<object width="' . $data['varx'] . '" height="' . $data['vary'] . '"><param name="movie" value="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $data['varx'] . '" height="' . $data['vary'] . '"></embed></object>';
        echo '</div></div></div></div></div>';
	break;
      case 6:
      echo '<div class="width_site width_'.$moduleID.'"><div class="inside_content">';
        echo '<div class="mod_youtube box  mod_youtube_6 mod_'.$moduleID.'" id="mod_'.$moduleID.'"><div class="margin"><div class="inside">';
        echo '<object width="' . $data['varx'] . '" height="' . $data['vary'] . '"><param name="movie" value="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $data['varx'] . '" height="' . $data['vary'] . '"></embed></object>';
        echo '</div></div></div></div></div>';
	break;
      case 7:
      echo '<div class="width_site width_'.$moduleID.'"><div class="inside_content">';
        echo '<div class="mod_youtube box  mod_youtube_7 mod_'.$moduleID.'" id="mod_'.$moduleID.'"><div class="margin"><div class="inside">';
        echo '<object width="' . $data['varx'] . '" height="' . $data['vary'] . '"><param name="movie" value="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $data['varx'] . '" height="' . $data['vary'] . '"></embed></object>';
        echo '</div></div></div></div></div>';
	break;
      case 8:
      echo '<div class="width_site width_'.$moduleID.'"><div class="inside_content">';
        echo '<div class="mod_youtube box  mod_youtube_8 mod_'.$moduleID.'" id="mod_'.$moduleID.'"><div class="margin"><div class="inside">';
        echo '<object width="' . $data['varx'] . '" height="' . $data['vary'] . '"><param name="movie" value="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $data['varx'] . '" height="' . $data['vary'] . '"></embed></object>';
        echo '</div></div></div></div></div>';
	break;
      case 9:
      echo '<div class="width_site width_'.$moduleID.'"><div class="inside_content">';
        echo '<div class="mod_youtube box  mod_youtube_9 mod_'.$moduleID.'" id="mod_'.$moduleID.'"><div class="margin"><div class="inside">';
        echo '<object width="' . $data['varx'] . '" height="' . $data['vary'] . '"><param name="movie" value="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $data['ident'] . '&hl=pl&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $data['varx'] . '" height="' . $data['vary'] . '"></embed></object>';
        echo '</div></div></div></div></div>';
	break;
    }
  }
}