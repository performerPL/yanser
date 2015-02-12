<?php
foreach ($Tab['menu_list'] as $key => $menu){
  $access=0;
  foreach ($Tab['menu_access'] as $key1 => $menu_access){
    if ($access==1) {
      continue;
    }
    if ($menu_access['menu_id']==$menu['menu_id']) {
      $access=1;
    }
  }
  _gui_checkbox('mod_allow_menu_access['.$menu['menu_id'].']',$T['allow_sitemap_menu_access'].$menu['menu_name'],1,$access,$Error['allow_upload']);
}