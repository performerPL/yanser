<?php if(!defined('_APP')) exit;?>

<div class="oper">
	<a href="edit_code_block.php?code_block_id=0#content" title="<?php _t('code_block_add'); ?>"><img src="img/icon_menu_add_m.gif" border="0" width="20" height="20" alt="" /><?php _t('code_block_add'); ?></a>
</div>

<div class="history">
	<img src="img/icon_menu.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<?php _t('code_blocks_mgmt'); ?>
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
<!--  index menu -->


<?php
_gui_stats($Stats);

if(count($Tab)>0) {
	?>
	<div class="content_block">
	<table class="data" cellspacing="1" width="100%">
	<tr>
		<th>Id</th>
		<th><?php _t('code_block_name'); ?></th>
		<th><?php _t('code_block_description'); ?></th>
		<th><?php _t('code_block_active'); ?></th>
		<th><?php _t('code_block_code'); ?></th>
		<th>&nbsp;</th>
	</tr>
	<?php
	$x=  0;
	foreach($Tab as $k=>$v) {
		?>
		<tr class="data_row<?php echo intval(($x%2)+1);?>">
			<td><?php echo htmlspecialchars($v['id']); ?></td>
			<td><?php echo htmlspecialchars($v['name']); ?></td>
			<td><?php echo htmlspecialchars($v['description']); ?></td>
			<td><?php if($v['active'] == 1) echo _t('yes'); ?></td>
			<td><?php echo htmlspecialchars($v['code']); ?></td>
			<td><a name="i_<?php echo intval($v['id']); ?>" href="edit_code_block.php?code_block_id=<?php echo intval($v['id']); ?>#content" title="<?php _t('code_block_edit'); ?>"><img src="img/icon_menu_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('code_block_edit'); ?></a></td>
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
<!--  /index menu -->




<br />