<?php if(!defined('_APP')) exit; ?>
<!--  lista zdjec w galerii z guzikami EDYCJA i USUN -->




<?php if($_REQUEST["type"] == "thumbnails") { /* MINIATURKI */?>

			<ul class="index" id="gallery_thumbs">
			<?php
				foreach($images as $image) {
			?>
				<li id="picture_<?=$image['picture_id'] ?>">
					<div class="picture_file_2"><img class="sorter" alt="<? echo$image['picture_file'];?>" src="<?=htmlspecialchars($GL_CONF["IMAGES_FILES"]["IMAGE_BASE_URL"] . $GL_CONF["IMAGES_FILES"]["IMAGE_DIR_1"] . $image['picture_file'])?>"></div>
					<div class="picture_title_2"><? echo (($image['picture_title'])=='' ? '<span style="color: #aaa;">'.$image['picture_file'].'</span>' : substr($image['picture_title'],0,20)); ?></div>
					<div class="picture_description_2"><? echo substr(htmlspecialchars($image['picture_description']),0,20); ?>...</div>
					<span class="" style="display:none;">
					<? 
					if ($image['picture_target_url']) {
					echo '<br />'.$T['picture_target_url'].' '.$image['picture_target_url'];
					}
					?>
					</span>
					<span class="picture_tools_2">
						<a onclick="new Ajax.Updater('galleryPictures', 'list_gallery.php?type=' + $('type').value, { parameters: { cmd: 'delete', ord: '<?=$image['picture_order']?>', gallery_id: '<?=intval($ID)?>' }, evalScripts: true });"><? _t("picture_delete") ?></a>
						<a onclick="new Ajax.Updater('picture_<?=$image['picture_id']?>', 'edit_gallery_picture_data.php', { parameters: { edit: 1, type: $('type').value, picture_id: <?=$image['picture_id']?> }, evalScripts: true });"><? _t("picture_edit"); ?></a>
					</span>
				</li>
			<?php
				}
			?>
			</ul>
			<script>
			//	Sortable.create('gallery_thumbs', {handle:'sorter', constraint: 'none', scroll:window,onUpdate: updateOrderPictures });
			</script>



<?php } else { /* LISTA ZDJEC bez miniatur */?>
<ul id="gallery_list">
<?php
	foreach($images as $image) {
?>
<li id="picture_<?=$image['picture_id'] ?>">

	<span class="picture_tools">
			<a class="red" onclick="new Ajax.Updater('galleryPictures', 'list_gallery.php?type=' + $('type').value, { parameters: { cmd: 'delete', ord: '<?=$image['picture_order']?>', gallery_id: '<?=intval($ID)?>' }, evalScripts: true });"><? _t("picture_delete") ?></a>
			<a class="green" onclick="new Ajax.Updater('picture_<?=$image['picture_id']?>', 'edit_gallery_picture_data.php', { parameters: { edit: 1, type: $('type').value, picture_id: <?=$image['picture_id']?> }, evalScripts: true });"><? _t("picture_edit"); ?></a>
	</span>
	

	<?php	
	
	/* Lista tytuly i opisy w edycji galerii (pierwsza ods³ona) */
	
	echo '<img src="img/icon_mod_gallery_m.gif" width="16" height="16" class="mod_icon" />';
	echo '<span class="picture_title">';
	echo (($image['picture_title'])=='' ? '<span style="color: #aaa;">'.$image['picture_file'].'</span>' : $image['picture_title']);
	echo '&nbsp;</span>';
	echo '<span class="picture_file">'.htmlspecialchars($image['picture_file']).'&nbsp;</span>';
	echo '<span class="picture_description">'.htmlspecialchars($image['picture_description']).'&nbsp;';
	if ($image['picture_target_url']) {
		echo '<br />'.$T['picture_target_url'].' '.$image['picture_target_url'].' ('.$image['picture_target'].')';
	}
	echo '</span>';
	?>
	

	
	
</li>
<?php
	}
?>
</ul>
<script>
	Sortable.create('gallery_list', {handle:'mod_icon',scroll:window,onUpdate: updateOrderPictures });
</script>
<?php } ?>