<?php if(!defined('_APP')) exit; ?>
<div class="history">
	<img src="img/icon_item_add.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<a href="index_item.php?menu_id=<?php echo intval($MenuID); ?>#content" title="<?php _t('content_mgmt'); ?>"><?php _t('content_mgmt'); ?></a>
	<?php _t('item_add'); 	
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
_gui_form_start('editFrm','','post');
	_gui_hidden('cmd','add');
	_gui_hidden('item_id',0);
	?>
	

	<?php
	$isAdd = true;
	require 'edit_item_base.html.php';
	_gui_break();
	_gui_form_row();
	echo '&nbsp;';
	_gui_form_row_mid();
		_gui_button($T['cancel'],'location.href=\'index_item.php?menu_id='.intval($MenuID).($ID>0?'#i_'.intval($ID):'#content').'\'');
		_gui_button($T['ok'],'','editFrm');
	_gui_form_row_end();
	_gui_select('next_step',$T['item_next_step'],$Tab['next_step'],$GL_ITEM_NSTEPS,'','translation_txt_func');
_gui_form_end();


?>
<div class="space">&nbsp;</div>
</div>