<?php if(!defined('_APP')) exit;  ?>
{nodes:[
<?php
	//var_dump($item_id);
	$x=0;
	foreach($Tab as $k=>$v) {
		$item = item_get_simple($k, true);
		echo $x>0?',':'';
		?>
		{
			id:'it_<?php echo intval($k); ?>',
			type:'<?php echo $v['children']>0?'item':'leaf'; ?>',
			data:{
				name:'<?php echo addslashes($v['item_name']); ?>',
				id:'<?php echo intval($k); ?>',
				type_img:'<?php echo addslashes($GL_ITEM_TYPE[$v['item_type']]['small_icon']); ?>',
				type_label:'<?php echo addslashes($T[$GL_ITEM_TYPE[$v['item_type']]['name']]); ?>',
				visible:<?php echo $v['visible']>0?'true':'false'; ?>,
				edit_label:'<?php echo addslashes($T['item_edit']); ?>',
				menu_id: '<?=$item['menu_id'] ?>',
				delete_label: '<?=addslashes($T['item_delete']); ?>'
			}
		}
		<?php
		++$x;
	}
?>
]}