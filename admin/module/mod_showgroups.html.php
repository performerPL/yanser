<?php
_gui_select('grupa',$T['mod_showgroups_grupa'],$Tab['grupa'],$Tab['GRUPY'],'','mod_showgroups_grupa_info');
_gui_text('wyniki',$T['mod_showgroups_wyniki'],$Tab['wyniki']);
_gui_text('strony',$T['mod_showgroups_strony'],$Tab['strony']);
_gui_checkbox('show_title', $T['mod_showgroups_show_title'],1,$Tab['show_title']>0);
_gui_checkbox('show_icon', $T['mod_showgroups_show_icon'],1,$Tab['show_icon']>0);
_gui_checkbox('show_date', $T['mod_showgroups_show_date'],1,$Tab['show_date']>0);
_gui_checkbox('show_date_mod', $T['mod_showgroups_show_date_mod'],1,$Tab['show_date_mod']>0);
_gui_checkbox('show_zajawka', $T['mod_showgroups_show_zajawka'],1,$Tab['show_zajawka']>0);
_gui_select('pokazuj',$T['mod_showgroups_pokazuj'],$Tab['pokazuj'],array(0 => 'najnowsze', 1=> 'najczęściej czytane'),'','mod_showgroups_pokazuj_info');
// pokazuj autora
_gui_select('show_author',$T['mod_showgroups_show_author'],$Tab['show_author'],$T['mod_showgroups_show_author_list']);