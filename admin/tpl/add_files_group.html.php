<? if(!defined('_APP')) exit; ?>
<div class="history">
	<img src="img/icon_gallery_add.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<? _t('main_menu'); ?>"><? _t('main_menu'); ?></a>
	<a href="index_ftp.php#content" title="<? _t('ftp_mgmt'); ?>"><? _t('ftp_mgmt'); ?></a>
	<? _t('mod_ftp_group_add');
	if(isset($Message) && $Message!='') {
		?>
		<div class="message">
			<?=$Message ?>
		</div>
		<?
	}
	?>
</div>
<div class="content_block">
<?
	_gui_form_start('editFrm','','post');
	_gui_hidden('cmd','add');
	_gui_hidden('group_id',0);
	_gui_text('group_name', $T['group_name'], $Tab['gallery_name'], 120, true, $Error['group_name']);
	_gui_textarea('group_description', $T['group_description'], $Tab['group_description'], 30, 5, WYSIWYG_SIMPLE, false, '', $T['group_description_info']);
	_gui_break();
	
	
echo '<div class="space"></div><div id="global_btn">';
		_gui_button($T['cancel'],'location.href=\'index_gallery.php#content\'');
		_gui_button($T['ok'],'','editFrm');
echo '</div>';
	
	
_gui_form_end();


?>
<div class="space"></div>
</div>