<?php if (!defined('_APP')) exit;?>

<div class="history">
	<img src="img/icon_menu.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<?php _t('opinions_mgmt'); ?>
</div>

<?php if (isset($Message) && $Message!=''): ?>
	<div class="message">
		<?php echo $Message; ?>
	</div>
	<?php endif ?>

<?php
if(count($Tab)>0) {
	?>
	<div class="content_block">
	<table class="data" cellspacing="1" width="100%">
	<tr>
		<th><a href="#" class="sortheader" onclick="ts_resortTable(this);return false;">#<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
		<th>ID strony</th>
		<th><a href="?order_by=1&order_type=<?php echo $out[orderTypeReversed][1] ?>"><?php _t('mod_opinions_itemname'); ?></a></th>
		<th><a href="?order_by=2&order_type=<?php echo $out[orderTypeReversed][2] ?>"><?php _t('mod_opinions_lastdate'); ?></a></th>
		<th><a href="?order_by=3&order_type=<?php echo $out[orderTypeReversed][3] ?>"><?php _t('mod_opinions_all'); ?></a></th>
		<th><a href="?order_by=4&order_type=<?php echo $out[orderTypeReversed][4] ?>"><?php _t('mod_opinions_waiting'); ?></a></th>
		<th>&nbsp;</th>
	</tr>
	<?php
	$x=  0;
	foreach($Tab as $k=>$v) {
		?>
		<tr class="data_row<?php echo intval(($x%2)+1);?>">
			<td><?=$v['module_id']?></td>
			<td><?php echo $v['item_id'] ?></td>
			<td><?php echo htmlspecialchars($v['item_name']); ?></td>
			<td><?php echo $v['last_date']?></td>
			<td><?php echo $v['all']; ?></td>
			<td><?php echo $v['waiting']; ?></td>
			<td style="width: 210px;"><a name="i_<?php echo intval($k); ?>" href="edit_opinions.php?module_id=<?php echo intval($v['module_id']); ?>#content" title="<?php _t('mod_opinions_edit'); ?>"><img src="img/icon_opinions_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('mod_opinions_edit'); ?></a></td>
		</tr>
		<?php
		++$x;
	}
	?>
	</table>
	
	
<div class="space"></div><div id="global_btn">
	<?php _gui_button($T['ok'], 'location.href=\'index.php\''); ?>
</div>	
	</div>
	<?php 
	// wyświetla stronnicowanie
	$module->getPaging($paging,$activity);
} else {
	?>
	<p class="message">
	<?php _t('no_opinions_msg'); ?>
	</p>
	<?php
}
?>

<br />