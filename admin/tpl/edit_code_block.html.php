<?php if(!defined('_APP')) exit; ?>

	<div class="oper">
		<?php if($ID > 0) {?>
		<a href="javascript:remove()" title="<?php _t('code_block_delete'); ?>" class="delete"><img src="img/icon_menu_delete_m.gif" width="20" height="20" alt="" border="0" /><?php _t('code_block_delete'); ?></a>		
	    <?php } ?>
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
	<a href="index_code_blocks.php<?php echo $ID>0?'#i_'.intval($ID):'#content'; ?>" title="<?php _t('code_blocks_mgmt'); ?>"><?php _t('code_blocks_mgmt'); ?></a>
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
		if(confirm('<?php addslashes(_t('code_block_delete_confirm')); ?>')) {
			document.deleteFrm.submit();
		}
	}
	</script>
	<?php
	_gui_form_start('deleteFrm','','post',false);
	_gui_hidden('cmd','delete');
	_gui_hidden('code_block_id',intval($ID));
	_gui_form_end(false);
}	

?>
<div class="content_block">
<?

_gui_form_start('editFrm','edit_code_block.php');
	_gui_hidden('cmd','edit');
	_gui_hidden('code_block_id',intval($ID));
	
	_gui_text('name',$T['code_block_name'],$Tab['name'],120,true,$Error['name']);
	_gui_textarea('description',$T['code_block_description'],$Tab['description'],50,10,WYSIWYG_NONE, true,'',$T['code_block_description']);
	_gui_textarea('code',$T['code_block_code'],$Tab['code'],50,10,WYSIWYG_NONE, true,'',$T['code_block_code']);
	_gui_checkbox('active',$T['code_block_active'],1,$Tab['active']>0,'');
	
	_gui_break();
echo '<div class="space"></div><div id="global_btn">';
		_gui_button($T['cancel'],'location.href=\'index_code_blocks.php'.($ID>0?'#i_'.intval($ID):'#content').'\'');
		_gui_button($T['ok'],'','editFrm');
echo '</div>';
_gui_form_end();


?>

</div><br />