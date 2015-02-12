<?php if(!defined('_APP')) exit; ?>
<input type="hidden" name="repo_type" id="repo_type" value="<?php echo intval($type); ?>" />
<div id="file_tree"></div>
<script type="text/javascript">
	<?php
	$x=0;
	$level=0;
	foreach($List as $k=>$v) {
		$type= is_dir($Dir.$v['file_name'])?'dir':'file';
		//$parent = $v['parent_id']>0?intval($v['parent_id']):'item_tree';
		?>
		new Ajax.Tree.Files('file_tree','<?php echo addslashes($v['file_name']); ?>','<?php echo $type; ?>',{data:{
			file_name:'<?php echo addslashes($v['file_name']); ?>',
			file_icon:'<?php echo addslashes($v['file_icon']); ?>',
			link_label:'<?php echo addslashes($T['selectImage']); ?>',
			rel_path:'<?php echo addslashes($v['rel_path']); ?>',
			link_func:'<?php echo $v['is_dir']?'':'javascript:selectImage'; ?>'
		}});
		<?php
	}
	?>
	function selectImage(x) {
		window.top.document.getElementById('<?php echo addslashes($field); ?>').value=x;
		window.top.hidePopWin();
	}
	
</script>

<div class="nav">
	<?php _gui_button($T['cancel'], 'window.top.hidePopWin()'); ?>
</div>
