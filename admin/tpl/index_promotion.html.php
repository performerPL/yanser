<?php if(!defined('_APP')) exit;?>
<div class="oper">
	<a href="edit_promotion.php?promotion_id=0#content" title="<?php _t('promotion_add'); ?>"><img src="img/icon_promotion_add_m.gif" border="0" width="20" height="20" alt="" /><?php _t('promotion_add'); ?></a>
</div>

<div class="history">
	<img src="img/icon_promotion.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<?php _t('promotion_mgmt'); ?>
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
?>

<div class="content_block">

<?
if(count($Tab)>0) {
	?>
	<table class="data" width="100%">
	<tr>
		<th><a href="#" class="sortheader" onclick="ts_resortTable(this);return false;"><?php _t('Promotion_name'); ?><span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
		<th><a href="#" class="sortheader" onclick="ts_resortTable(this);return false;"><?php _t('Promotion_code'); ?><span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
		<th><?php _t('Article_group'); ?></th>
		<th><?php _t('Allow_endless'); ?></th>
		<th><?php _t('Rss'); ?></th>
		<th>&nbsp;</th>
	</tr>
	<?php
	$x=  0;
	foreach($Tab as $k=>$v) {
		?>
		<tr class="data_row<?php echo intval(($x%2)+1); echo $v['active']>0?'':' off'; ?>">
			<td><?php echo htmlspecialchars($v['name']); ?></td>
			<td><?php echo htmlspecialchars($v['promotion_code']); ?></td>
			<td><?php echo $v['article_group']>0?GUI_YES_IMG:GUI_NO_IMG; ?></td>
			<td><?php echo $v['allow_endless']>0?GUI_YES_IMG:GUI_NO_IMG; ?></td>
			<td><?php echo $v['rss']>0?GUI_YES_IMG:GUI_NO_IMG; ?></td>
			<td><a name="i_<?php echo intval($k); ?>" href="edit_promotion.php?promotion_id=<?php echo intval($k); ?>#content" title="<?php _t('promotion_edit'); ?>"><img src="img/icon_promotion_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('promotion_edit'); ?></a></td>
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
	<?php _t('no_promotions_msg'); ?>
	</p>
	<?php
}
?>

<div class="space"></div><div id="global_btn">
	<?php _gui_button($T['ok'], 'location.href=\'index.php\''); ?>
</div>

</div><br />

