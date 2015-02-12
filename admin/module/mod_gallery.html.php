<?php
// pobiera scieżku do zdjec
$cfg = $GL_CONF['IMAGES_FILES'];

$res = gallery_list();
$galleries = array('' => 'nie wybrano');
foreach($res as $gallery)
  $galleries[$gallery["gallery_id"]] = $gallery["gallery_name"] . "(" . $gallery["count"] . ")"; 
_gui_select('gallery_id', $T['mod_gallery_gallery_id'], $Tab['gallery_id'], $galleries);
_gui_checkbox('show_title',$T['mod_gallery_show_title'],1,!isset($Tab['show_title']) || $Tab['show_title'] > 0);
_gui_checkbox('show_description',$T['mod_gallery_show_desc'],1,!isset($Tab['show_description']) || $Tab['show_description'] > 0);
// nazwa galerii
_gui_checkbox('show_gallery_name',$T['mod_gallery_show_gallery_name'],1,$Tab['show_gallery_name'] > 0);
// opis galerii
_gui_checkbox('show_gallery_description', $T['mod_gallery_show_gallery_description'], 1, !isset($Tab['show_gallery_description']) || $Tab['show_gallery_description'] > 0);
_gui_checkbox('show_target_url', $T['mod_gallery_show_target_url'], 1, !isset($Tab['show_target_url']) || $Tab['show_target_url'] > 0);
// pokazuj powiekszenie
_gui_checkbox('show_enlarge', $T['mod_gallery_show_enlarge'], 1, $Tab['show_enlarge'] > 0);
// pokazuj powiekszenie lightbox
_gui_checkbox('show_enlarge_lightbox', $T['mod_gallery_show_enlarge_lightbox'], 1, $Tab['show_enlarge_lightbox'] > 0);
// pokazuj ilość zdjęć w galerii
_gui_checkbox('show_pictures_counter', $T['mod_gallery_show_pictures_counter'], 1, $Tab['show_pictures_counter'] > 0);
?>
<div class="row">
  <div class="row_left">Typ obrazka:</div>
  <div class="row_right">
<label><input type="radio" name="image_type" value="<?=htmlspecialchars($cfg["IMAGE_DIR_1"]) ?>" <?php if ($Tab['image_type'] == htmlspecialchars($cfg["IMAGE_DIR_1"]) || empty($Tab['image_type'])): ?>checked="checked"<?php endif ?>>małe</label>
<label><input type="radio" name="image_type" value="<?=htmlspecialchars($cfg["IMAGE_DIR_2"]) ?>" <?php if ($Tab['image_type'] == htmlspecialchars($cfg["IMAGE_DIR_2"])): ?>checked="checked"<?php endif ?>>średnie</label>
<label><input type="radio" name="image_type" value="<?=htmlspecialchars($cfg["IMAGE_DIR_3"]) ?>" <?php if ($Tab['image_type'] == htmlspecialchars($cfg["IMAGE_DIR_3"])): ?>checked="checked"<?php endif ?>>duże</label>
<label><input type="radio" name="image_type" value="<?=htmlspecialchars('gallery/orig/') ?>" <?php if ($Tab['image_type'] == htmlspecialchars('gallery/orig/')): ?>checked="checked"<?php endif ?>>oryginalne</label>
  </div>
</div>

