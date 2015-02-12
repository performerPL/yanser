<?php
$types = array(
  0 => $T['mod_include_type_0'],
  1 => $T['mod_include_type_1']
);
_gui_select('include_type', $T['mod_include_include_type'], $Tab['include_type'], $types);
_gui_text('include_addr',$T['mod_include_include_addr'],$Tab['include_addr'], false, false, false, $T['mod_include_include_addr_info']);
