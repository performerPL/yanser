<?php 
if (!defined('_APP')) {
  exit; 
}
?>
<div class="history">
	<img src="img/icon_gallery_add.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<a href="index_gallery.php#content" title="<?php _t('gallery_mgmt'); ?>"><?php _t('gallery_mgmt'); ?></a>
	<?php
	 _t('gallery_add'); 	
	if (isset($Message) && $Message!='') {
		?>
		<div class="message">
			<?=$Message ?>
		</div>
		<?php
	}
	?>
</div>
<?php
	_gui_form_start('editFrm','','post');
	_gui_hidden('cmd','add');
	_gui_hidden('gallery_id',0);
	_gui_text('gallery_name', $T['gallery_name'], $Tab['gallery_name'], 120, true, $Error['gallery_name']);
	_gui_textarea('gallery_description', $T['gallery_description'], $Tab['gallery_description'], 30, 5, WYSIWYG_SIMPLE, false, '', $T['gallery_description_info']);
	_gui_break();
	_gui_form_row();
	echo '&nbsp;';
	_gui_form_row_mid();
		_gui_button($T['cancel'],'location.href=\'index_gallery.php#content\'');
		_gui_button($T['ok'],'','editFrm');
	_gui_form_row_end();
_gui_form_end();

?>
<div class="space"></div>