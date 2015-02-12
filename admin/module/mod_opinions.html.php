<?php
_gui_checkbox('moderation',$T['mod_opinions_moderation_title'],1,!isset($Tab['moderation']) || $Tab['moderation'] > 0);
_gui_text('per_page',$T['per_page'],$Tab['per_page'], false, false, false, $T['per_page_info']);
_gui_link("zobacz dostepne opinie dla tej strony","edit_opinions.php?module_id=".$Tab['article_id'],"_blank");
