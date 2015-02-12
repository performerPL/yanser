<? if(!defined('_APP')) exit; ?>
<!--  lista zdjec w galerii z edycja danych i guzikami ZAPISZ ANULUJ -->





<? if($_REQUEST["type"] == "thumbnails") {   /* MINIATURKI */?>
	
	
			<div class="picture_file_2">
			<img class="sorter" alt="<? echo$image['picture_file'];?>" src="<?=htmlspecialchars($GL_CONF["IMAGES_FILES"]["IMAGE_BASE_URL"] . $GL_CONF["IMAGES_FILES"]["IMAGE_DIR_1"] . $image['picture_file'])?>">
			</div>
			<div class="picture_title_2">
			<? 
			if($edit) { 
			echo '<input id="picture_title_' . $ID . '" type="text"  name="picture_title" value="' . htmlspecialchars($image['picture_title']) . '" />'; 
			} else {
			//echo htmlspecialchars($image['picture_title'])
			echo (($image['picture_title'])=='' ? '<span style="color: #aaa;">'.$image['picture_file'].'</span>' : substr($image['picture_title'],0,20));
			}
			?>
			</span><br />
			
			<span class="picture_description_2">
			<? 
			if($edit) { 
			echo '<div  style="display:none;"><input id="picture_description_' . $ID . '" type="text" name="picture_description" value="' . htmlspecialchars($image['picture_description']) . '" /></span>'; 
			} else {
			echo substr(htmlspecialchars($image['picture_description']),0,20).'...';
			} 
			?>
			</div>
			
			<span class="" style="display:none;"><? if($edit) { echo $T['picture_target_url'].' <input id="picture_target_url_' . $ID . '" type="text" name="picture_target_url" value="' . htmlspecialchars($image['picture_target_url']) . '" />'; } else echo htmlspecialchars($image['picture_target_url'])?>&nbsp;</span>
			<span class="" style="display:none;"><?	_gui_select('picture_target', $T['picture_target'], $Tab['picture_target'], $pictureTargetList,'','',false,'','',array('id' => 'picture_target_' . $ID)); ?>&nbsp;</span>
			
			<span class="picture_tools_2">
			<? if($edit) { /* Edycja gelerii: MINIATURKI - edycja tytulu i podpisu po zmianie danych */ ?>
				<a onclick="new Ajax.Updater('picture_<?=$image['picture_id']?>', 'edit_gallery_picture_data.php', { parameters: { edit: 0, type: $('type').value, picture_id: <?=$image['picture_id']?> }, evalScripts: true });"><? _t("cancel"); ?></a>
				<a onclick="new Ajax.Updater('picture_<?=$image['picture_id']?>', 'edit_gallery_picture_data.php', { parameters: { edit: 0, cmd: 'edit', picture_title: $('picture_title_<?=$ID ?>').value, picture_description: $('picture_description_<?=$ID ?>').value, picture_target_url: $('picture_target_url_<?=$ID ?>').value, picture_target: $('picture_target_<?=$ID ?>').value, type: $('type').value, picture_id: <?=$image['picture_id']?> }, evalScripts: true });"><? _t("submit"); ?></a>
			<? } else { ?>
				<a onclick="new Ajax.Updater('galleryPictures', 'list_gallery.php?type=' + $('type').value, { parameters: { cmd: 'delete', ord: '<?=$image['picture_order']?>', gallery_id: '<?=intval($ID)?>' }, evalScripts: true });"><? _t("picture_delete") ?></a>
				<a onclick="new Ajax.Updater('picture_<?=$image['picture_id']?>', 'edit_gallery_picture_data.php', { parameters: { edit: 1, type: $('type').value, picture_id: <?=$image['picture_id']?> }, evalScripts: true });"><? _t("picture_edit"); ?></a>
			<? } ?>
			</span>
	
	
	
<? } else { /* LISTA ZDJEC bez miniatur */?>

			<? if($edit) { ?>
			<form name="picture_data_<?=$ID ?>">


			<? } ?>
				<img src="img/icon_mod_gallery_m.gif" width="16" height="16" class="mod_icon" />
				
				
				<span class="picture_title">
				<? if($edit) {      /* Edycja gelerii: Tytu³ na liscie w czasie edycji i po */
					echo '<input id="picture_title_' . $ID . '" type="text"  name="picture_title" value="' . htmlspecialchars($image['picture_title']) . '" />'; 
				} else 
					echo (($image['picture_title'])=='' ? '<span style="color: #aaa;">'.$image['picture_file'].'</span>' : $image['picture_title']);
				?>
				
				&nbsp;</span>
				
				
				<span class="picture_tools">
				<? if($edit) { ?>
					<a onclick="new Ajax.Updater('picture_<?=$image['picture_id']?>', 'edit_gallery_picture_data.php', { parameters: { edit: 0, type: $('type').value, picture_id: <?=$image['picture_id']?> }, evalScripts: true });"><? _t("cancel"); ?></a>
					<a class="green" onclick="new Ajax.Updater('picture_<?=$image['picture_id']?>', 'edit_gallery_picture_data.php', { parameters: { edit: 0, cmd: 'edit', picture_title: $('picture_title_<?=$ID ?>').value, picture_description: $('picture_description_<?=$ID ?>').value, picture_target_url: $('picture_target_url_<?=$ID ?>').value, picture_target: $('picture_target_<?=$ID ?>').value, type: $('type').value, picture_id: <?=$image['picture_id']?> }, evalScripts: true });"><? _t("submit"); ?></a>
					<? } else { ?>
					<a class="red" onclick="new Ajax.Updater('galleryPictures', 'list_gallery.php?type=' + $('type').value, { parameters: { cmd: 'delete', ord: '<?=$image['picture_order']?>', gallery_id: '<?=intval($ID)?>' }, evalScripts: true });"><? _t("picture_delete") ?></a>
					<a class="green" onclick="new Ajax.Updater('picture_<?=$image['picture_id']?>', 'edit_gallery_picture_data.php', { parameters: { edit: 1, type: $('type').value, picture_id: <?=$image['picture_id']?> }, evalScripts: true });"><? _t("picture_edit"); ?></a>
					<? } ?>
				
				</span>
				
				
				<span class="picture_file">11<?=htmlspecialchars($image['picture_file'])?>&nbsp;</span>
				<span class="picture_description">
				<? if($edit) { echo '<textarea id="picture_description_' . $ID . '" name="picture_description" >' . htmlspecialchars($image['picture_description']) . '</textarea>'; 
				} else {
				echo htmlspecialchars($image['picture_description']);
					if ($image['picture_target_url']) {
						echo '<br />'.$T['picture_target_url'].' '.$image['picture_target_url'].' ('.$image['picture_target'].')';
					}	
				}?>
				
				</span>
				<div class="space"></div>

				<? if($edit) {      /* Edycja opisu i tytu³ów w galerii */
						echo '<span style="width: 31px; float: left;">&nbsp;</span>';
						echo '<span class="picture_title">'.$T['picture_target_url'].'</span> <input id="picture_target_url_' . $ID . '" type="text" name="picture_target_url" value="' . htmlspecialchars($image['picture_target_url']) . '" style="width: 220px;"/>';
						echo '<select name="picture_target" class="in" id="picture_target_' . $ID . '">';
						echo '<option value="self" '.($image['picture_target']=='self' ? 'selected="selected"':'').'>w tym samym oknie</option>';
						echo '<option value="blank" '.($image['picture_target']=='blank'? 'selected="selected"':'').'>w nowym oknie</option>';
						echo '</select>'; 
						
						//_gui_select('picture_target', $T['picture_target'], $Tab['picture_target'], $pictureTargetList,'','',false,'','',array('id' => 'picture_target_' . $ID));
				 } else { 
						// echo htmlspecialchars($image['picture_target_url']);
				}		
				?>	
				
				

				<script>
					Sortable.create('gallery_list', {handle:'mod_icon',scroll:window,onUpdate: updateOrderPictures });
				</script>
				
				
			<? if($edit) { ?>
			</form>
		<? } ?>
<? } ?>