<?php 
if (!defined('_APP')) {
  exit;
}
?>

<div class="oper">
	<a href="edit_template.php?template_id=0#content" title="<?php _t('template_add'); ?>"><img src="img/icon_template_add_m.gif" border="0" width="20" height="20" alt="" /><?php _t('template_add'); ?></a>
</div>
<div class="history">
	<img src="img/icon_template.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<?php _t('template_mgmt'); ?>
</div>

<?php if (isset($Message) && $Message != ''): ?>
	<div class="message">
		<?php echo $Message; ?>
	</div>
<?php endif ?>



<div class="content_block">
<?php
//_gui_stats($Stats);

if(count($Tab)>0) {
	?>
	<table class="data" width="100%">
	<tr>
		<th><?php _t('Template_name'); ?></th>
		<th><?php _t('Template_dir'); ?></th>
		<th><?php _t('Template_default'); ?></th>
		<th><?php _t('Template_info'); ?></th>
		<th>&nbsp;</th>
	</tr>
	<?php
	$x = 0;
	foreach($Tab as $k=>$v) {
		?>
		<tr class="data_row<?php echo intval(($x%2)+1); echo $v['active'] > 0 ? '' : ' off'; ?>">
			<td><?php echo htmlspecialchars($v['template_name']); ?></td>
			<td><?php echo htmlspecialchars($v['template_dir']); ?></td>
			<td><?php echo $v['template_def'] > 0 ? GUI_YES_IMG : ''; ?></td>
			<td><?php echo nl2br(htmlspecialchars($v['info'])); ?></td>
			<td><a name="i_<?php echo intval($k); ?>" href="edit_template.php?template_id=<?php echo intval($k); ?>#content" title="<?php _t('template_edit'); ?>"><img src="img/icon_template_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('template_edit'); ?></a></td>
		</tr>
		<?php
		++$x;
	}
	?>
	</table>
	<!-- nawigacja po stronacch ewentualnie<div class="navbar">
	</div>-->
	<?php
} else {
	?>
	<p class="message">
	<?php _t('no_template_msg'); ?>
	</p>
	<?php
}
?>

<div class="space"></div><div id="global_btn">
	<?php _gui_button($T['ok'], 'location.href=\'index.php\''); ?>
</div>
</div><br />

