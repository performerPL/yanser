<?php if(!defined('_APP')) exit;?>
<div class="history">
	<img src="img/icon_menu.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<a href="index_opinions.php#content" title="<?_t('opinions_mgmt');?>"><?_t('opinions_mgmt');?></a>
	<?php _t('mod_opinions_edit'); ?>
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

if(count($Tab)>0) {
	?>
	<div class="content_block">
	<table class="data" cellspacing="1" width="100%">
	<tr>
		<th><?php _t('mod_opinions_opinion'); ?></th>
		<th><?php _t('mod_opinions_nick'); ?></th>
		<th><?php _t('mod_opinions_ip'); ?></th>
		<th><?php _t('mod_opinions_status'); ?></th>
		<th><?php _t('mod_opinions_created'); ?></th>
		<th><?php _t('mod_opinions_updated'); ?></th>
		<th>&nbsp;</th>
	</tr>
	<?php
	$x=  0;
	foreach($Tab as $k=>$v) {
		?>
		<tr class="data_row<?php echo intval(($x%2)+1);?>">
			<td><?=htmlspecialchars($v['opinion'])?></td>
			<td><?=htmlspecialchars($v['nick']) ?></td>
			<td><?=htmlspecialchars($v['ip']) ?></td>
			<td><?php _t('mod_opinions_status_' . $v['status']); ?></td>
			<td><?=htmlspecialchars($v['created'])?></td>
			<td><?=htmlspecialchars($v['updated'])?></td>
			<td>
				<a href="edit_opinions.php?module_id=<?=$opinion['module_id']?>&cmd=delete&opinion_id=<?=$v['opinion_id']?>" title="<?_t('mod_opinions_delete');?>" onclick="return confirm('<?_t('mod_opinions_confirm_delete')?>')"><img src="img/icon_opinion_delete_m.gif" width="20" height="20" alt="" border="0" /></a>
			<? if($v['status'] != OPINION_DENIED) { ?>
				<a href="edit_opinions.php?module_id=<?=$opinion['module_id']?>&cmd=deny&opinion_id=<?=$v['opinion_id']?>" title="<?_t('mod_opinions_deny');?>" onclick="return confirm('<?_t('mod_opinions_confirm_deny')?>')"><img src="img/icon_opinion_deny_m.gif" width="20" height="20" alt="" border="0" /></a>
			<? }
			   if($v['status'] != OPINION_APPROVED) { ?>
				<a href="edit_opinions.php?module_id=<?=$opinion['module_id']?>&cmd=accept&opinion_id=<?=$v['opinion_id']?>" title="<?_t('mod_opinions_accept');?>" onclick="return confirm('<?_t('mod_opinions_confirm_accept')?>')"><img src="img/icon_opinion_accept_m.gif" width="20" height="20" alt="" border="0" /></a>
			<? } ?>
			</td>
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
	<?php _t('no_opinions_msg'); ?>
	</p>
	<?php
}
?>

