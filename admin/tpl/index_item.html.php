<?php 
if (!defined('_APP')) {
  exit;
}

function print_item ($k, $v, &$x, $level, $ShowLevel) 
  {
	global $Tab, $T, $GL_ITEM_TYPE, $MenuID;
	$type = count($Tab[$k]['subitems']) > 0 ? 'item' : 'leaf';
	$parent = $v['parent_id'] > 0 ? intval($v['parent_id']) : 'item_tree';
	?>
		'data':{
			'name':'<?php echo addslashes($v['item_name']);?>',
			'id':'<?php echo intval($k); ?>',
			'type_img':'<?php if ($v['page_start'] > 0) echo 'img/item_start_m.gif'; else echo addslashes($GL_ITEM_TYPE[intval($v['item_type'])]['small_icon']);?>',
			'type_label':'<?php echo addslashes($T[$GL_ITEM_TYPE[$v['item_type']]['name']]);?>',
			'visible':<?php echo $v['visible'] > 0 ? 'true' : 'false'; ?>,
			'edit_label':'<?php echo addslashes($T['item_edit']); ?>',
			'menu_id':'<?=$MenuID ?>',
			'delete_label':'<?=addslashes($T['item_delete']); ?>',
		}
		<?php
		if ($level < $ShowLevel && is_array($Tab[$k]['subitems']) && 1 == 2) {
			?>
			,'nodes':[
				<?php
					$y=0;
					foreach ($Tab[$k]['subitems'] as $k2 => $v2) {
						echo $y>0?',':'';
						?>
						{
							id:'it_<?php echo intval($k2); ?>',
							type:'<?php echo $v2['children'] > 0 ? 'item' : 'leaf'; ?>',
							<?php
							print_item($k2, $v2, $x, $level+1, $ShowLevel);
							?>
						}
						<?php
						++$y;
					}
				?>
			]
			<?php
		}
		?>
	<?php
	++$x;
}

?>

<div class="oper" id="oper_parent" style="margin-right: 40px; padding-left: 5px;">
	<a href="add_item.php?menu_id=<?php echo intval($MenuID); ?>&item_id=0#content" title="<?php _t('item_add'); ?>"><img src="img/icon_item_add_m.gif" border="0" width="20" height="20" alt="" /><?php _t('item_add'); ?></a>&nbsp;&nbsp;&nbsp;
</div>

<div class="history">
	<img src="img/icon_item.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<?php _t('content_mgmt'); ?>
</div>

<?php if (isset($Message) && $Message != ''):	?>
	<div class="message">
		<?php echo $Message; ?>
	</div>
	<?php endif ?>

<div class="content_block">

<?php _gui_stats($Stats); ?>
<div class="search">
	<form action="" method="get">
	<?php
	_gui_select_field('menu_id', 'menu_id', $MenuID, $Menus, '', 'menu_txt_func', array('onchange' => 'this.form.submit()'));
	?>
	&nbsp;<label for="archive_1">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class="in check" name="archive" id="archive_1" value="1" <?php echo $ShowArchive>0?' checked ':''; ?> onclick="this.form.submit()" />&nbsp;<?php _t('item_show_archive'); ?></label>
	 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	<input type="text" name="go_to_id" class="in" value="ID" style="width: 90px;"/> <input class="btn" type="submit" name="go_to_pos" value="skocz do edycji" /> 
	</form>
</div>


<?php if (count($Tab[0]['subitems'])>0): ?>
	<div id="item_tree"></div>
	<script type="text/javascript">
		
		<?php
		$x=0;
		$level=0;
		foreach ($Tab[0]['subitems'] as $k => $v) {
			$type = count($Tab[$k]['subitems']) > 0 ? 'item' : 'leaf';
			//$parent = $v['parent_id']>0?intval($v['parent_id']):'item_tree';
			?>
			new Ajax.Tree.Items('item_tree', 'it_<?php echo intval($k); ?>', '<?php echo $type; ?>', {<?php print_item($k, $v, $x, $level, $ShowLevel); ?>});
			<?php
		}
		?>
		Sortable.create('item_tree', {tag:'div',only:['treenode'],handle:'item_info_img',scroll:window,onUpdate: updateOrderItem});
	</script>
	<!-- nawigacja po stronacch ewentualnie<div class="navbar">
	</div>-->
	<?php else:	?>
	<p class="message">
	<?php _t('no_item_msg'); ?>
	</p>
	<?php endif ?>

<div class="space"></div><div id="global_btn">
	<?php _gui_button($T['ok'], 'location.href=\'index.php\''); ?>
</div>
</div><br />

