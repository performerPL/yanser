<?php
if (!defined('_APP')) {
  exit; 
}
?>
<?php
	if (isset($images)):
?>
<a style="cursor: pointer;" onclick="new Ajax.Updater('addbody', 'add_group_files.php?cmd=list&list=');"><?php _t('mod_ftp_group_list_back'); ?></a>
<input type="hidden" name="list" value="<?=$_REQUEST["list"]?>" />
<ul class="index_files">
<?php
	$cfg = $GL_CONF["IMAGES_FILES"];
	//print_r($images);
	foreach ($images as $image):
?>
	<li>
  		<input type="checkbox" name="add_file[]" value="<?=htmlspecialchars($image['file_id'])?>"/>
		<span class="file_name">...<?php _t('mod_ftp_filename'); ?>: <?=htmlspecialchars($image['file_name'])?></span>		
	</li>	
	<li>
		<span class="file_title"><?php _t('mod_ftp_title'); ?> <?=htmlspecialchars($image['file_title'])?></span>
	</li>
	<li>
		<span class="file_title"><?php _t('mod_ftp_description'); ?> <?=htmlspecialchars($image['file_description'])?></span>
	</li>
	<hr style="clear:both">
<?php endforeach ?>
<br style="clear: both;"/>
</ul>

<?php else: ?>

<b><?php _t('mod_ftp_group_list'); ?></b><br /><br />
<ul>

<?php
	$res = group_list();
	foreach ($res as $tab) {
		echo '<li><a style="cursor: pointer;" onclick="new Ajax.Updater(\'addbody\', \'add_group_files.php?cmd=list&list=' . $tab['group_id'] . '\');">';
		echo htmlspecialchars($tab['group_name']);
		echo '</a></li>';
	}
	echo '<li><a style="cursor: pointer;" onclick="new Ajax.Updater(\'addbody\', \'add_group_files.php?cmd=list&list=/\');">pokaż wszystkie</a></li>';
	echo '<li><a style="cursor: pointer;" onclick="new Ajax.Updater(\'addbody\', \'add_group_files.php?cmd=list&list=0\');">Nieprzypisane pliki</a></li>';
?>	
</ul>
<?php endif ?>