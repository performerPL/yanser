<?php
	_gui_form_start('addImageFrm','','post', true, true);
	_gui_hidden('cmd','add');
	_gui_hidden('gallery_id', $ID);
	_gui_text('picture_title', $T['picture_title'], $Tab['picture_title'], 120, false, $Error['picture_title']);
	_gui_textarea('picture_description', $T['picture_description'], $Tab['picture_description'], 30, 5, WYSIWYG_NONE, false, '', '');
	_gui_text('picture_target_url', $T['picture_target_url'], $Tab['picture_target_url'], 120, false, $Error['picture_target_url']);
	_gui_select('picture_target', $T['picture_target'], $Tab['picture_target'], $pictureTargetList);
?>
	<div class="row">
				<div class="row_left"><label for="picture_file"><?php _t('picture_file'); ?></label></div>
				<div class="row_right"><input type="file" id="picture_file" name="picture_file" onChange="image_exists(this.value);"><div id="picture_file_error"></div></div>
	</div>
	<div class="row">
				<div class="row_left"></div>
				<div class="row_right">
				<input type="checkbox" id="orginal" name="original" value="1"/> Zachowaj oryginał
				</div>
	</div>
	
	
	
<?php
	_gui_break();
echo '<div class="space"></div><div id="global_btn">';
		_gui_button($T['cancel'],'cancel_i()');
		_gui_button($T['ok'],'', 'addImageFrm');
echo '</div>';
		
_gui_form_end();


?>