<?php 
if (!defined('_APP')) {
  exit;
}
?>

<div class="oper">
	<a href="edit_user.php?user_id=0#content" title="<?php _t('user_add'); ?>"><img src="img/icon_user_add_m.gif" border="0" width="20" height="20" alt="" /><?php _t('user_add'); ?></a>
</div>

<div class="history">
	<img src="img/icon_user.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<?php _t('user_mgmt'); ?>
</div>

<?php
if (isset($Message) && $Message != '') {
	?>
	<div class="message">
		<?php echo $Message; ?>
	</div>
	<?php
}
?>



<div class="content_block">
<?php
//_gui_stats($Stats);

if (count($Tab) > 0) {
	?>
	<table class="data">
	<tr>
		<th><?php _t('User_name'); ?></th>
		<th><?php _t('Email'); ?></th>
		<th><?php _t('Phone'); ?></th>
		<th><?php _t('User_login'); ?></th>
		<th><?php _t('User_info'); ?></th>
		<th><?php _t('Access_level'); ?></th>
		<th><?php _t('Allow_upload'); ?></th>
<?php if (_sec_authorised(ACCESS_MIN_ADMIN)): ?><th>&nbsp;</th><?php endif ?>
		<th>&nbsp;</th>
	</tr>
	<?php
	$x=  0;
	foreach($Tab as $k=>$v) {
		?>
		<tr class="data_row<?php echo intval(($x%2)+1); echo $v['active']>0?'':' off'; ?>">
			<td><?php echo htmlspecialchars($v['user_name']); ?></td>
			<td><?php echo htmlspecialchars($v['email']); ?></td>
			<td><?php echo htmlspecialchars($v['phone']); ?></td>
			<td><?php echo htmlspecialchars($v['login']); ?></td>
			<td><?php echo nl2br(htmlspecialchars($v['info'])); ?></td>
			<td><?php echo htmlspecialchars($GL_ACCESS_LVL[$v['access_level']]); ?></td>
			<td><?php echo $v['allow_upload']>0?GUI_YES_IMG:GUI_NO_IMG; ?></td>
<?php if (_sec_authorised(ACCESS_MIN_ADMIN)): ?><td><a href="edit_user_access.php?user_id=<?php echo intval($k); ?>#content" title="<?php _t('user_access'); ?>"><img src="img/icon_user_access_m.gif" border="0" width="20" height="20" alt="" /><?php _t('Access'); ?></a></td><?php endif ?>
			<td><a name="i_<?php echo intval($k); ?>" href="edit_user.php?user_id=<?php echo intval($k); ?>#content" title="<?php _t('user_edit'); ?>"><img src="img/icon_user_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('user_edit'); ?></a></td>
		</tr>
		<?php
		++$x;
	}
	?>
	</table>
	<!-- nawigacja po stronacch ewentualnie<div class="navbar">
	</div>-->
	<div class="space"></div><div id="global_btn">
	<?php _gui_button($T['ok'], 'location.href=\'index.php\''); ?>
</div>
	</div><br />
	<?php
} else {
	?>
	<p class="message">
	<?php _t('no_users_msg'); ?>
	</p>
	<?php
}
?>

