<?php
// pokazuj tytuły
_gui_checkbox('show_title',$T['mod_subitems_show_title'],1,$Tab['show_title']>0);
// pokazuj opisy
_gui_checkbox('show_description',$T['mod_subitems_show_desc'],1,$Tab['show_description']>0);
// pokazuj ikony
_gui_checkbox('show_icon',$T['mod_subitems_show_icon'],1,$Tab['show_icon']>0);
// pokazuj zawartośc podstrony
_gui_checkbox('show_content',$T['mod_subitems_show_content'],1,$Tab['show_content']>0);
// pokazuj ilośc podstron dla każdej pozycji
_gui_checkbox('show_subitems_counter',$T['mod_subitems_show_subitems_counter'],1,$Tab['show_subitems_counter']>0);
// pokazuj date utworzenia
_gui_checkbox('show_date',$T['mod_subitems_show_date'],1,$Tab['show_date']>0);
// pokazuj date modyfikacji
_gui_checkbox('show_date_mod',$T['mod_subitems_show_date_mod'],1,$Tab['show_date_mod']>0);
// pokazuj autora
_gui_select('show_author',$T['mod_subitems_show_author'],$Tab['show_author'],$T['mod_subitems_show_author_list']);
// pokazuj popularność
_gui_checkbox('show_popularity',$T['mod_subitems_show_popularity'], 1, $Tab['show_popularity'] > 0);
// pokazuj sortowanie
_gui_checkbox('show_sort',$T['mod_subitems_show_sort'], 1, $Tab['show_sort'] > 0);

// domyślne sortowanie
$mod_subitems_obj = new mod_subitems($Tab['show_sort_type']);
_gui_select('show_sort_type',$T['mod_subitems_show_sort_type'],$mod_subitems_obj->getSortType(),$mod_subitems_obj->getSortTypeList());
// ilość pozycji
_gui_text('show_per_page',$T['mod_subitems_show_per_page'],$Tab['show_per_page']);
// ilość podstron artykulu
_gui_text('show_article_id',$T['mod_subitems_show_article_id'],$Tab['show_article_id']);
