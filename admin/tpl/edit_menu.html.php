<?php 
if (!defined('_APP')) {
  exit; 
}
?>	

<div class="oper">
		<a href="javascript:remove()" title="<?php _t('menu_delete'); ?>" class="delete"><img src="img/icon_menu_delete_m.gif" width="20" height="20" alt="" border="0" /><?php _t('menu_delete'); ?></a>
		
	</div>
<div class="history">
	<?php
	if ($ID > 0) {
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
	<a href="index_menu.php<?php echo $ID>0?'#i_'.intval($ID):'#content'; ?>" title="<?php _t('menu_mgmt'); ?>"><?php _t('menu_mgmt'); ?></a>
	<?php _t('menu_edit'); 	
	if (isset($Message) && $Message != '') {
	?>
	
	<div class="message">
		<?php echo $Message; ?>
	</div>
	<?php
}
	?>
</div>

<?php
if ($ID > 0) {
	?>

	
	

	<script type="text/javascript">
	function remove() {
		if(confirm('<?php addslashes(_t('menu_delete_confirm')); ?>')) {
			document.deleteFrm.submit();
		}
	}
	</script>
	<?php
	_gui_form_start('deleteFrm','','post',false);
	_gui_hidden('cmd','delete');
	_gui_hidden('menu_id',intval($ID));
	_gui_form_end(false);
	_gui_stats(array(
		$T['id'] => $Tab['menu_id'],
	));
}	

?>
<div class="content_block">
<?php

_gui_form_start('editFrm','edit_menu.php');
	_gui_hidden('cmd','edit');
	_gui_hidden('menu_id',intval($ID));
	
	_gui_text('menu_name',$T['menu_name'],$Tab['menu_name'],120,true,$Error['menu_name']);
	_gui_text('menu_code',$T['menu_code'],$Tab['menu_code'],50,true,$Error['menu_code']);
	_gui_select('lang_id',$T['language'],$Tab['lang_id'],$GL_CONF['LANG'],'','lang_id_txt_func');
	function lang_id_txt_func($k,$v) {
		return htmlspecialchars($v['LANG_NAME']);
	}
	_gui_checkbox('show_in_map',$T['show_in_map'],1,$Tab['show_in_map']>0,$Error['show_in_map']);

	
	_gui_break('Wartości dodatkowe:');
	
		for ($key=0; $key < 10; $key++) {
			_gui_form_row(false);
				?>
				<input type="text" class="in" style="width: 150px;margin-left:150px;" value="<?=$Tab['addons'][$key]['name']?>" maxlength="255" name="menu_addons_name[<?=$key?>]" />&nbsp;
				<input type="text" class="in" style="width:150px" value="<?=$Tab['addons'][$key]['value']?>" maxlength="255" name="menu_addons[<?=$key?>]" />
				<?php
			_gui_form_row_end(false);
		}
	
echo '<div class="space"></div><div id="global_btn">';
		_gui_button($T['cancel'],'location.href=\'index_menu.php'.($ID>0?'#i_'.intval($ID):'#content').'\'');
		_gui_button($T['ok'],'','editFrm');
echo '</div>';
_gui_form_end();

?>

</div><br />