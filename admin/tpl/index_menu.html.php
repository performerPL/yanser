<?php if(!defined('_APP')) exit;?>

<div class="oper">
	<a href="edit_menu.php?menu_id=0#content" title="<?php _t('menu_add'); ?>"><img src="img/icon_menu_add_m.gif" border="0" width="20" height="20" alt="" /><?php _t('menu_add'); ?></a>
</div>

<div class="history">
	<img src="img/icon_menu.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<?php _t('menu_mgmt'); ?>
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




<?php
_gui_stats($Stats);

if(count($Tab)>0) {
	?>
	<div class="content_block">
	<table class="data" cellspacing="1" width="100%">
	<tr>
		<th><a href="#" class="sortheader" onclick="ts_resortTable(this);return false;"><?php _t('Menu_name'); ?><span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
		<th><?php _t('Menu_code'); ?></th>
		<th><?php _t('Language'); ?></th>
		<th><?php _t('Show_in_map'); ?></th>
		<th>&nbsp;</th>
	</tr>
	<?php
	$x=  0;
	foreach($Tab as $k=>$v) {
		?>
		<tr class="data_row<?php echo intval(($x%2)+1);?>">
			<td><?php echo htmlspecialchars($v['menu_name']); ?></td>
			<td><?php echo htmlspecialchars($v['menu_code']); ?></td>
			<td><img src="<?php echo htmlspecialchars($GL_CONF['LANG'][$v['lang_id']]['LANG_FLAG']); ?>" alt="<?php echo htmlspecialchars($v['lang_id']); ?>" width="16" /><?php echo htmlspecialchars($GL_CONF['LANG'][$v['lang_id']]['LANG_NAME']); ?></td>
			<td><?php echo $v['show_in_map']>0?GUI_YES_IMG:GUI_NO_IMG; ?></td>
			<td><a name="i_<?php echo intval($k); ?>" href="edit_menu.php?menu_id=<?php echo intval($k); ?>#content" title="<?php _t('menu_edit'); ?>"><img src="img/icon_menu_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('menu_edit'); ?></a></td>
		</tr>
		<?php
		++$x;
	}
	?>
	</table>
	
<div class="space"></div><div id="global_btn">
	<?php _gui_button($T['ok'], 'location.href=\'index.php\''); ?>
</div>	
	
	</div><br />
	<!-- nawigacja po stronacch ewentualnie<div class="navbar">
	</div>-->
	<?php
} else {
	?>
	<p class="message">
	<?php _t('no_menu_msg'); ?>
	</p>
	<?php
}
?>

