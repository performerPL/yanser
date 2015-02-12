<?php 
if(!defined('_APP')) {
  exit;
}
if (count($Modules) > 0) {
	foreach ($Modules as $k => $v) {
		?>
		<li <?php echo $v['active'] > 0 ? '' : ' class="off" ';?> id="mod_<?php echo intval($k); ?>">
			<img src="<?php echo htmlspecialchars($GL_MOD_TYPE[$v['module_type']]->small_icon); ?>" width="16" height="16" alt="<?php echo htmlspecialchars($T[$GL_MOD_TYPE[$v['module_type']]->name]); ?>" class="mod_icon" />
			<span ><?php echo htmlspecialchars($v['module_name']); ?></span>
			<a href="javascript:void(0)" onclick="popup_url('edit_module.php?article_id=<?php echo intval($ArticleID); ?>&module_id=<?php echo intval($v['module_id']); ?>&module_type=<?php echo intval($v['module_type']); ?>')"><?php _t('item_module_edit'); ?></a>
			<a href="#" onclick="if(!confirm('Czy napewno usunac modul?')) return false; new Ajax.Request('edit_module.php', {parameters: {cmd: 'delete', module_id: '<?=intval($v['module_id'])?>'}, onSuccess: function(a) {if(a.responseText) alert(a.responseText); updateModuleList('', 0);}}); return false;"><?php _t('item_module_delete'); ?></a>
		</li>
		<?php
	}
}
