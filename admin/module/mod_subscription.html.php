<div style="margin: 0 auto;">
<?php
// pokazuj autora
_gui_checkbox('show_author',$T['item_show_author'],1,$Tab['show_author']>0);
// pokazuj zrodlo
_gui_checkbox('show_source',$T['item_show_source'],1,$Tab['show_source']>0);
// pokazuj date utworzenia
_gui_checkbox('show_date_create',$T['mod_subitems_show_date'],1,$Tab['show_date_create']>0);
// pokazuj date modyfikacji
_gui_checkbox('show_date_update',$T['mod_subitems_show_date_mod'],1,$Tab['show_date_update']>0);
?>
</div>