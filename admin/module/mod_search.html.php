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
  _gui_checkbox('mod_allow_menu_access['.$menu['menu_id'].']',$T['allow_menu_access'].$menu['menu_name'],1,$access,$Error['allow_upload']);
}
_gui_break();
_gui_checkbox('show', 'Pokazuj formularz wyszukiwarki', 1, ($Tab['show'] == 1) ? true : false);
_gui_break();
_gui_checkbox('s_title', 'Pokazuj tytul w wynikach', 1, ($Tab['s_title'] == 1) ? true : false);
_gui_checkbox('s_descr', 'Pokazuj opis w wynikach', 1, ($Tab['s_descr'] == 1) ? true : false);
_gui_checkbox('s_icon', 'Pokazuj ikone w wynikach', 1, ($Tab['s_icon'] == 1) ? true : false);
_gui_break();
// _gui_text('count', 'Ilosc wynikow per strona', $Tab['count']);
_gui_checkbox('group', 'Szukaj w grupach', 1, ($Tab['group'] == 1) ? true : false);
