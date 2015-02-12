<?php
$cfg = $GL_CONF['IMAGES_FILES'];

_gui_form_row();
_gui_form_row_mid();
_gui_form_row_end();
  
_gui_select('styl', $T['mod_shortcut_style'], $Tab['styl'], array(1=>1, 2=>2, 3=>3));
// end podpis pod ikoną JH 11.08.2008
_gui_form_row();
echo '&nbsp;';
_gui_form_row_mid();
_gui_form_row_end();