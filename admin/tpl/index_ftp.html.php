<? if(!defined('_APP')) exit;?>

<div class="oper">
	<a href="add_files_group.php#content" title="<? _t('mod_ftp_group_add'); ?>"><img src="img/icon_gallery_add_m.gif" border="0" width="20" height="20" alt="" /><? _t('mod_ftp_group_add'); ?></a>
</div>

<div class="history">
	<img src="img/icon_gallery.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<? _t('main_menu'); ?>"><? _t('main_menu'); ?></a>
	<? _t('ftp_mgmt'); ?>
</div>

<?
if(isset($Message) && $Message!='') {
	?>
	<div class="message">
		<?=$Message; ?>
	</div>
	<?
}
?>



<div class="content_block">
<?
_gui_stats($Stats);

if(count($Tab)>0) {
	?>
	<table  class="data" style="width:100%">
	<tbody>
	<tr>
		<th> <a href="#" class="sortheader" onclick="ts_resortTable(this);return false;">id<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
		<th> <a href="#" class="sortheader" onclick="ts_resortTable(this);return false;"><? _t('group_name'); ?><span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
		<th>&nbsp;</th>
	</tr>

  
	<?
	$x=  0;
	foreach($Tab as $k=>$v) {
		?>
		<tr class="data_row<?=intval(($x%2)+1)?>">
			<td><?=htmlspecialchars($v['group_id']); ?></td>
			<td><?=htmlspecialchars($v['group_name']); ?></td>
			<td>
				<a name="i_<?=intval($k); ?>" href="edit_group.php?group_id=<?=$v['group_id']?>#content" title="<? _t('group_edit'); ?>"><img src="img/icon_gallery_edit_m.gif" border="0" width="20" height="20" alt="" /><? _t('group_edit'); ?></a>
			</td>
		</tr>
		<?
		++$x;
	}
	?>
	</tbody>
	</table>
	<?
} else {
	?>
	<p class="message">
	<? _t('mod_ftp_no_group'); ?>
	</p>
	<?
}
?>

<div class="space"></div><div id="global_btn">
	<? _gui_button($T['ok'], 'location.href=\'index.php\''); ?>
</div>
</div><br />

