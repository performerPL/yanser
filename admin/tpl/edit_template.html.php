<?php 
if (!defined('_APP')) {
  exit; 
}
?>	

<div class="oper">
		<a href="javascript:remove()" title="<?php _t('template_delete'); ?>" class="delete"><img src="img/icon_template_delete_m.gif" width="20" height="20" alt="" border="0" /><?php _t('template_delete'); ?></a>
		
	</div>
<div class="history">
	<?php if ($ID>0): ?>
		<img src="img/icon_template_edit.gif" width="64" height="64" border="0" alt="" /> 
		<?php else: ?>
		<img src="img/icon_template_add.gif" width="64" height="64" border="0" alt="" /> 
		<?php endif	?>
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<a href="index_template.php<?php echo $ID > 0 ? '#i_'.intval($ID) : '#content'; ?>" title="<?php _t('template_mgmt'); ?>"><?php _t('template_mgmt'); ?></a>
	<?php _t('template_edit'); 	
	if(isset($Message) && $Message!='') {
	?>
	<div class="message">
		<?php echo $Message; ?>
	</div>
	<?php
}
	?>
</div>

<div class="content_block">

<?php
if ($ID>0) {
	?>

	<script type="text/javascript">
	function remove() {
		if(confirm('<?php addslashes(_t('template_delete_confirm')); ?>')) {
			document.deleteFrm.submit();
		}
	}
	</script>
	<?php
	_gui_form_start('deleteFrm','','post',false);
	_gui_hidden('cmd','delete');
	_gui_hidden('template_id',intval($ID));
	_gui_form_end(false);
	_gui_stats(array(
		$T['id'] => $Tab['template_id'],
	));
}	

_gui_form_start('editFrm','edit_template.php');
	_gui_hidden('cmd','edit');
	_gui_hidden('template_id',intval($ID));
	
	_gui_checkbox('active',$T['template_active'],1,$Tab['active']>0);
	_gui_checkbox('template_def',$T['template_default'],1,$Tab['template_def']>0);
	_gui_text('template_name',$T['template_name'],$Tab['template_name'],150,true,$Error['template_name']);
	_gui_select('template_dir',$T['template_dir'],$Tab['template_dir'],$Templates,'','',true,$Error['template_dir'],$T['template_dir_info']);
	_gui_textarea('info',$T['template_info'],$Tab['info']);
	_gui_break();
	
	if($Tab['menu_list']) {
			foreach ($Tab['menu_list'] as $key => $menu){
			$access=0;
				foreach ($Tab['menu_access'] as $key1 => $menu_access){
					if ($access==1) { continue; 	}
					if ($menu_access['menu_id']==$menu['menu_id']) { 	$access=1; 	}
				}
				_gui_checkbox('mod_allow_menu_access['.$menu['menu_id'].']',$T['allow_menu_access'].$menu['menu_name'],1,$access,$Error['allow_upload']);
			}
	} else {
	echo '<div class="space"></div><b>UWAGA:</b> Po dodaniu nowego szablonu należy wejść w <b>EDYCJĘ</b> i przypisać do niego <b>paski menu</b><br /><br />';
	}
	
	_gui_break();
echo '<div class="space"></div><div id="global_btn">';
		_gui_button($T['cancel'],'location.href=\'index_template.php'.($ID>0?'#i_'.intval($ID):'#content').'\'');
		_gui_button($T['ok'],'','editFrm');
echo '</div>';
_gui_form_end();

?>
<div class="space"></div>
</div><br />