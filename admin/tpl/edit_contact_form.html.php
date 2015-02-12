<?php if(!defined('_APP')) exit; ?>

	<div class="oper">
		<a href="javascript:remove()" title="<?php _t('contact_form_delete'); ?>" class="delete"><img src="img/icon_menu_delete_m.gif" width="20" height="20" alt="" border="0" /><?php _t('contact_form_delete'); ?></a>
		
	</div>
	
	
<div class="history">
	<?php
	if($ID>0) {
		?>
		<img src="img/icon_menu_edit.gif" width="64" height="64" border="0" alt="" /> 
		<?php
	} else {
		?>
		<img src="img/icon_menu_add.gif" width="64" height="64" border="0" alt="" /> 
		<?php
	}
	?>
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<a href="index_contact_forms.php<?php echo $ID>0?'#i_'.intval($ID):'#content'; ?>" title="<?php _t('contact_forms_mgmt'); ?>"><?php _t('contact_forms_mgmt'); ?></a>
	<?php _t('menu_edit'); 	
	if(isset($Message) && $Message!='') {
	?>
	
	<div class="message">
		<?php echo $Message; ?>
	</div>
	<?php
}
	?>
</div>

<?php
if($ID>0) {
	?>

	
	

	<script type="text/javascript">
	function remove() {
		if(confirm('<?php addslashes(_t('contact_form_delete_confirm')); ?>')) {
			document.deleteFrm.submit();
		}
	}
	</script>
	<?php
	_gui_form_start('deleteFrm','','post',false);
	_gui_hidden('cmd','delete');
	_gui_hidden('form_id',intval($ID));
	_gui_form_end(false);
	_gui_stats(array(
		$T['id'] => $Tab['menu_id'],
	));
}	

?>
<div class="content_block">
<?

_gui_form_start('editFrm','edit_contact_form.php');
	_gui_hidden('cmd','edit');
	_gui_hidden('form_id',intval($ID));
	
	_gui_text('form_type_name',$T['form_type_name'],$Tab['form_type_name'],120,true,$Error['form_type_name']);
	_gui_textarea('form_type_html',$T['form_type_html'],$Tab['form_type_html'],50,10,WYSIWYG_NONE, true,'',$T['form_type_html']);
	
	_gui_break();
echo '<div class="space"></div><div id="global_btn">';
		_gui_button($T['cancel'],'location.href=\'index_contact_forms.php'.($ID>0?'#i_'.intval($ID):'#content').'\'');
		_gui_button($T['ok'],'','editFrm');
echo '</div>';
_gui_form_end();


?>

</div><br />