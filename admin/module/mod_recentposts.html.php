<?php
_gui_text('forum_id',$T['mod_recentposts_forum_id'],$Tab['forum_id'], false, false, false, $T['per_page_info']);
//_gui_text('topic_id',$T['mod_recentposts_topic_id'],$Tab['topic_id'], false, false, false, $T['per_page_info']);
_gui_text('count',$T['mod_recentposts_count'],$Tab['count'], false, false, false, $T['per_page_info']);

$what = array(
  1 => 'Najnowsze',
  2 => 'Najczęściej czytane'
);

_gui_select('what', $T['mod_recentposts_what'], $Tab['what'], $what);