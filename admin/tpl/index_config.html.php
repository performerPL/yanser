	<?php if(!defined('_APP')) exit;?>
<div class="history">
	<img src="img/icon_config.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<a href="index_config_value.php" title="<?php _t('config_value_mgmt'); ?>"><?php _t('config_value_mgmt'); ?></a>
	<?php _t('config_mgmt'); ?>
</div>

<?php
if(isset($Message) && $Message!='') {
	?>
	<div class="message">
		<?php echo $Message; ?>
	</div>
	<?php
}
?>

<div class="oper">
	<a href="edit_config.php?config_id=0#content" title="<?php _t('config_add'); ?>"><img src="img/icon_config_add_m.gif" border="0" width="20" height="20" alt="" /><?php _t('config_add'); ?></a>
</div>


<?php
_gui_stats($Stats);

if(count($Tab)>0) {
	?>
	<table class="data">
	<tr>
		<th colspan="2"><?php _t('Config_icon'); ?></th>
		<th><?php _t('Config_name'); ?></th>
		<th><?php _t('Config_code'); ?></th>
		<th><?php _t('Config_info'); ?></th>
		<!--<th><?php _t('Config_parent'); ?></th>-->
		<th><?php _t('Config_regex'); ?></th>
		<th><?php _t('Config_is_group'); ?></th>
		<th><?php _t('Config_multiple'); ?></th>
		<th><?php _t('Allow_edit'); ?></th>
		<th>&nbsp;</th>
	</tr>
	<?php
	
	function config_display_row($v,$k,$x,$main=true) {
		//global $Tab;
		?>
		<tr class="data_row<?php echo intval(($x%2)+1); ?>">
			<?php
			if($v['parent_id']>0) {
				?>
				<td>&nbsp;</td>
				<td><?php echo (trim($v['config_icon'])!='' && file_exists($v['config_icon']))?'<img src="'.htmlspecialchars($v['config_icon']).'" border="0" alt="icon" />':'&nbsp;'; ?></td>
				<?php
			} else {
				?>
				<td><?php echo (trim($v['config_icon'])!='' && file_exists($v['config_icon']))?'<img src="'.htmlspecialchars($v['config_icon']).'" border="0" alt="icon" />':'&nbsp;'; ?></td>
				<td>&nbsp;</td>
				<?php
			}
			?>
			<td><?php echo htmlspecialchars($v['config_name']); ?></td>
			<td><?php echo htmlspecialchars($v['config_code']); ?></td>
			<td><?php echo nl2br(htmlspecialchars($v['info'])); ?></td>
			<!--<td><?php echo $v['parent_id']>0?htmlspecialchars($Tab[$v['parent_id']]['config_name'].' ('.$Tab[$v['parent_id']]['config_code'].')'):'&nbsp;'; ?></td>-->
			<td><?php echo htmlspecialchars($v['config_regex']); ?></td>
			
			<td><?php echo $main?($v['is_group']>0?GUI_YES_IMG:GUI_NO_IMG):'&nbsp;'; ?></td>
			<td><?php echo $main?($v['multiple']>0?GUI_YES_IMG:GUI_NO_IMG):'&nbsp;'; ?></td>
			<td><?php echo $main?($v['allow_edit']>0?GUI_YES_IMG:GUI_NO_IMG):'&nbsp;'; ?></td>
			
			<td><a name="i_<?php echo intval($k); ?>" href="edit_config.php?config_id=<?php echo intval($k); ?>#content" title="<?php _t('config_edit'); ?>"><img src="img/icon_config_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('config_edit'); ?></a></td>
		</tr>
		<?php
	}
	
	
	
	$x=  0;
	foreach($Tab as $k=>$v) {
		config_display_row($v,$k,$x);
		++$x;
		if(is_array($v['subconfig'])) {
			foreach($v['subconfig'] as $sub_k=>$sub_v) {
				config_display_row($sub_v,$sub_k,$x,false);
				++$x;
			}
		}	
	}
	?>
	</table>
	<!-- nawigacja po stronacch ewentualnie<div class="navbar">
	</div>-->
	<?php
} else {
	?>
	<p class="message">
	<?php _t('no_configs_msg'); ?>
	</p>
	<?php
}
?>

<div class="nav">
	<?php _gui_button($T['ok'], 'location.href=\'index_config_value.php#content\''); ?>
</div>