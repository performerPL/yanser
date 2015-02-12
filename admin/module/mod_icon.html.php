<?php
// lista z typem targetu
$iconTargetList = array(
"self" => "self",
"blank" => "blank"
);

$cfg = $GL_CONF["IMAGES_FILES"];

_gui_form_row();
_gui_form_row_mid();
_gui_form_row_end();
// podpis pod ikoną  
_gui_textarea('icon_description',$T['mod_image_image_description'],$Tab['icon_description'],30,5,WYSIWYG_SIMPLE, false,'',$T['mod_image_image_description_info']);
// end podpis pod ikoną JH 11.08.2008
// scieżka do przekierowania po kliknieciu
_gui_text('icon_target_url', $T['picture_target_url'], $Tab['icon_target_url'], 120, false, $Error['icon_target_url']);
// typ przekierowania
_gui_select('icon_target', $T['picture_target'], $Tab['icon_target'], $iconTargetList);
// zdjecie z powiekszeniem
_gui_checkbox('show_enlarge', $T['mod_gallery_show_enlarge'], 1, $Tab['show_enlarge'] > 0);


_gui_form_row();
echo '&nbsp;';
_gui_form_row_mid();
_gui_form_row_end();
 ?>






