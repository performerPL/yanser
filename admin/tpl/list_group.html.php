<?php 
if (!defined('_APP')) {
  exit; 
}
?>
<?php if($_REQUEST["type"] == "thumbnails") { ?>
<ul class="index" id="gallery_thumbs">
<?php
	foreach($images as $image) {
?>
	<li id="picture_<?=$image['picture_id'] ?>">
		<img class="sorter"  src="<?=htmlspecialchars($GL_CONF["IMAGES_FILES"]["IMAGE_BASE_URL"] . $GL_CONF["IMAGES_FILES"]["IMAGE_DIR_1"] . $image['picture_file'])?>">
		<span class="title"><?=htmlspecialchars($image['picture_title'])?></span>
		<span class="info"><?=htmlspecialchars($image['picture_description'])?></span>
		<span>
			<a onclick="new Ajax.Updater('galleryPictures', 'list_gallery.php?type=' + $('type').value, { parameters: { cmd: 'delete', ord: '<?=$image['picture_order']?>', gallery_id: '<?=intval($ID)?>' }, evalScripts: true });"><? _t("picture_delete") ?></a>
			<a onclick="new Ajax.Updater('picture_<?=$image['picture_id']?>', 'edit_gallery_picture_data.php', { parameters: { edit: 1, type: $('type').value, picture_id: <?=$image['picture_id']?> }, evalScripts: true });"><? _t("picture_edit"); ?></a>
		</span>
	</li>
<?php
	}
?>
</ul>

<?php } else { ?>

<ul id="file_list">
<?php
	//print_r($images);
	foreach ($images as $image) {
?>
<li id="file_<?=$image['file_id'] ?>">
	<img src="img/icon_mod_gallery_m.gif" width="16" height="16" class="mod_icon" />
	<span class="file_name">
		 <?php if ($image['file_type'] == 'directory') {	?>
		 <a href="?group_id=<?=$ID?>&dir=<?=$dir.'/'.$image['file_name']?>"><?=htmlspecialchars($image['file_name'])?> </a>&nbsp;</span>
		<?	} else { ?>
		<?=htmlspecialchars($image['file_name'])?> &nbsp;</span>
		<?php }?>
	<span class="file_title"><?=htmlspecialchars($image['file_title'])?>&nbsp;</span>
	<span class="file_description"><?=htmlspecialchars($image['file_description'])?>&nbsp;</span>
	<span class="fole_tools">
	<a href="javascript:void(0)" onclick="showForma(<?=$image['file_id']?>, <?=$ID?>)" ><? _t("mod_ftp_file_edit") ?></a> | 
		 <?php if ($image['file_type'] == 'directory')
			{?><a href="#" onclick="if (window.confirm('Czy napewno chcesz usunąć tą pozycję?')) window.location='edit_group.php?cmd=delete&file_id=<?=$image['file_id']?>&file_type=directory&file_name=<?=$image['file_name']?>&group_id=<?=$ID?>'"><? _t("mod_ftp_file_delete") ?></a><?} else {?><a href="#" onclick="if (window.confirm('Czy napewno chcesz usunąć tą pozycję?')) window.location='edit_group.php?cmd=delete&file_id=<?=$image['file_id']?>&file_type=file&file_name=<?=$image['file_name']?>&group_id=<?=$ID?>&file_dir=<?=$dir?>'"><? _t("mod_ftp_file_delete") ?></a><?}?>	
	| <a href="#" onclick="if (window.confirm('Czy napewno chcesz usunąć tą pozycję?')) window.location='edit_group.php?cmd=delete_filegroup&file_id=<?=$image['file_id']?>&group_id=<?=$ID?>'"><? _t("mod_ftp_file_group_delete") ?></a></span>
	
</li>
<?php
	}
?>
</ul>
<script>
	Sortable.create('file_list', {handle:'mod_icon',scroll:window,onUpdate: updateOrderFiles });
</script>
<?php } ?>