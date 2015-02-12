<?php
// limit wierszy
_gui_text('row_limit',$T['mod_show_www_catalog_top_row_limit'],$Tab['row_limit']);
// grupy ogloszeń
_gui_select('www_catalog_group_id',$T['mod_show_www_catalog_top_www_catalog_groups'],$Tab['www_catalog_group_id'],$Tab['topGroups']);
// pokazuj tytuły
_gui_checkbox('show_title',$T['mod_show_www_catalog_top_show_title'],1,$Tab['show_title']>0);
// pokazuj opisy
_gui_checkbox('show_description',$T['mod_show_www_catalog_top_show_description'],1,$Tab['show_description']>0);
// pokazuj linki
_gui_checkbox('show_url',$T['mod_show_www_catalog_top_show_url'],1,$Tab['show_url']>0);