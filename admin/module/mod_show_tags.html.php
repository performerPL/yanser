<div style="margin: 0 auto;">
<?php
// limit
_gui_text('limit',$T['mod_show_tags_limit'],$Tab['limit']);
// id wyszukiwarki
_gui_text('search_article_id',$T['mod_show_tags_search_article_id'],$Tab['search_article_id']);

// pokaz wg alfabetu
_gui_select('show_alphabetically',$T['mod_show_tags_show_alphabetically'],$Tab['show_alphabetically'],$T['mod_show_tags_show_alphabetically_list'],'','',false,'');
// pokaz wg popularnosci
//_gui_select('show_popularity',$T['mod_show_tags_show_popularity'],$Tab['show_popularity'],$T['mod_show_tags_show_alphabetically_list'],'','',false,'');
// pokaz ilosc wystapien
_gui_checkbox('show_hits', $T['mod_show_tags_show_hits'],1,$Tab['show_hits']>0);

?>
</div>