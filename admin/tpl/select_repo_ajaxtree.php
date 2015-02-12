<?php if(!defined('_APP')) exit;  ?>
{nodes:[
<?php
	//var_dump($item_id);
	$x=0;
	foreach($List as $k=>$v) {
		echo $x>0?',':'';
		?>
		{
			id:'<?php echo addslashes($v['file_name']); ?>',
			type:'<?php echo $v['is_dir']?'dir':'file'; ?>',
			data:{
				file_name:'<?php echo addslashes($v['file_name']); ?>',
				file_icon:'<?php echo addslashes($v['file_icon']); ?>',
				rel_path:'<?php echo addslashes($v['rel_path']); ?>',
				link_label:'<?php echo addslashes($T['selectImage']); ?>',
				link_func:'<?php echo $v['is_dir']?'':'javascript:selectImage'; ?>'
			}
		}
		<?php
		++$x;
	}
?>
]}