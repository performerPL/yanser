<?php 
if (!defined('_APP')) {
  exit; 
}
?>
	<div class="oper">
		<a href="javascript:remove()" title="<?php _t('group_delete'); ?>" class="delete"><img src="img/icon_gallery_delete_m.gif" width="20" height="20" alt="" border="0" /><? _t('group_delete'); ?></a>
	</div>
<div class="history">
		<img src="img/icon_gallery_edit.gif" width="64" height="64" border="0" alt="" /> 
		<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
		<a href="index_ftp.php#content" title="<?php _t('ftp_mgmt'); ?>"><?php _t('ftp_mgmt'); ?></a>
		<?php _t('group_edit'); 	
		if (isset($Message) && $Message != ''): ?>
					<div class="message">
						<?=$Message ?>
					</div>
		<?php endif	?>
</div>

<div class="content_block">
				<script type="text/javascript">
				function remove() 
				{
					if (confirm('<?php addslashes(_t('group_delete_confirm')); ?>')) {
						document.deleteFrm.submit();
					}
				}
				</script>
				<?php
				_gui_form_start('deleteFrm', '', 'post', false);
				_gui_hidden('cmd', 'delete');
				_gui_hidden('group_id', intval($ID));
				_gui_form_end(false);
				//_gui_stats(array( $T['id'] => $Tab['gallery_id'], ));
				?>
			<form name="editFrm" id="editFrm" method="POST" action="" enctype="multipart/form-data">
			<input type="hidden" name="cmd" value="edit" id="i_cmd"/>
			<?php
				_gui_hidden('group_id', $Tab['group_id']);
				?>
				<input type="hidden" name="tajny_dir" id="tajny_dir" value="<?php echo $dir ?>"/>
				<?php
				_gui_text('group_name', $T['group_name'], $Tab['group_name'], 120, true, $Error['group_name']);
				_gui_textarea('group_description', $T['group_description'], $Tab['group_description'], 30, 5, WYSIWYG_SIMPLE, false, '', $T['group_description_info']);
				_gui_break();
				// dostep do serwisow
				foreach ($Tab['menu_list'] as $key => $menu){
					$access = 0;
					foreach ($Tab['menu_access'] as $key1 => $menu_access){
						if ($access == 1) {
							continue;
						}
						if ($menu_access['menu_id'] == $menu['menu_id']){
							$access=1;
						}
					}
					_gui_checkbox('allow_menu_access['.$menu['menu_id'].']',$T['allow_menu_access'].$menu['menu_name'],1,$access,$Error['allow_upload']);
				}
					
				_gui_break();	
			echo '<div class="space"></div><div id="global_btn">';
					_gui_button($T['cancel'],'location.href=\'index_ftp.php#content\'');
					_gui_button($T['ok'],'','editFrm');
			echo '</div>';
			_gui_form_end();

			?>

<br /><br />
<div class="content_block" style="background: #f4faff">
		<input type="hidden" id="type" value="list"/>		
		<a onclick="$('type').value = 'list'; updateFilesList();" style="float:left; padding: 8px 20px 0 20px;"><?php _t("mod_ftp_files_list"); ?></a>		
		<?php	
					$dir_array = explode('/', $dir);
					$array_len = count($dir_array);	
					$dir_acum='';
					$url_dir = $array_len == 2 ? '' : '&dir=';	
					for ($i = 1; $i < $array_len - 1; $i++) { 
						$url_dir .= '/' . $dir_array[$i];
						$full_path .= '/' . $dir_array[$i];
					}		
		?>
		<?php if ($full_path . '/' . $dir_array[$array_len-1] != '/'):?><a href="edit_group.php?group_id=<?=$ID?><?=$url_dir?>#content" title="<?php _t('mod_ftp_dir_up'); ?>"><img src="img/icon_gallery_add_m.gif" border="0" width="20" height="20" alt="" /><?php _t('mod_ftp_dir_up'); ?></a><?php endif ?>

		<div class="file_mngt">
					<?php if (!_sec_authorised(ACCESS_MIN_ADMIN)): ?>
					<form name="new_dir" method="POST" action="">
						<input type="text" name="dir_name" />
						<input type="hidden" name="cmd" value="add_dir"/>
						<input type="hidden" name="dir_curr" value="<?echo $dir?>"/>
						<input type="submit" value="<?php _t('group_create_directory'); ?>"/>
					</form>
					<?php endif ?>
					<a style="float:right; padding: 5px 40px 5px 0;" href="#" onclick="load_i('add_group_file.php?group_id=<?=$ID ?>&dir=<?=$dir?>'); document.getElementById('i_cmd').value = 'add';" title="<? _t('group_upload_file'); ?>" class="add_image"><img src="img/icon_gallery_add_image_m.gif" width="20" height="20" alt="" border="0" /><? _t('group_upload_file'); ?></a>
					<a style="float:right; padding: 5px 40px 5px 0;" href="#" onclick="load_i('add_group_files.php?group_id=<?=$ID ?>', true)" title="<?php _t('group_add_file'); ?>" class="add_image"><img src="img/icon_gallery_add_images_m.gif" width="20" height="20" alt="" border="0" /><?php _t('group_add_file'); ?></a>
				<div class="space"></div>
		</div>
</div>


<div class="content_block" style="display:none" id="upload_photo"><div class="space"></div></div>		
	
<div class="content_block">
			<div id="galleryPictures"><div class="space"></div></div>


			<script type="text/javascript">
			function load_i(url, galeria) 
			{
				var x = new Ajax.Updater("upload_photo", url, {
								method: "get",
								onComplete: function(){ 
									//document.getElementById('addImageFrm').action = url;
									if (galeria == true) {
										new Ajax.Updater('addbody', 'add_group_files.php?cmd=list&list=');
										//document.getElementById('editFrm2').action = url;
									}
								}
				 });
				 document.getElementById('upload_photo').style.display='block';
			}

			function cancel_i()
			{
				document.getElementById('upload_photo').style.display='none';
				document.getElementById('i_cmd').value = 'edit';
			}

			function submit_i()
			{
			$('addImageFrm').request({
						method: 'post',
						onComplete: function(){ 
							cancel_i();
							 }
						});
			}

			function updateFilesList() 
			{
				new Ajax.Updater('galleryPictures', 'list_files.php?dir=<?=$dir?>&type=' + $('type').value + '&group_id=<?=intval($ID)?>', { evalScripts: true });
			}

			var action = '';

			function showForma(plik, grupa)
			{
				//document.getElementById('edycja_pliku').style.display = 'block';
				new Ajax.Updater('file_' + plik, 'edit_group.php?cmd=edit_file&file_id=' + plik + '&group_id=' + grupa);
				action = 'edit_group.php?cmd=edit_file&file_id=' + plik + '&group_id=' + grupa;
			}

			function anulujForma(plik, grupa)
			{
				new Ajax.Updater('file_' + plik, 'edit_group.php?cmd=show_file&file_id=' + plik + '&group_id=' + grupa + '&direk=<?php echo $dir ?>');
			}
			function submitForma(plik, grupa)
			{
				document.getElementById("editFileFrm_" + plik).action = action;
				document.getElementById("cmd").value = 'edit_file';
				$('editFileFrm_' + plik).request({
						method: 'post',
						onComplete: function(){ 
							//document.getElementById('edycja_pliku').style.display = 'none';
							//document.getElementById("editFileFrm_" + plik).action = "";
							action = '';
							 //updateFilesList();
							 document.getElementById("cmd").value = 'edit';
							 new Ajax.Updater('file_' + plik, 'edit_group.php?cmd=show_file&file_id=' + plik + '&group_id=' + grupa + '&direk=<?php echo $dir ?>');
							 }
						});
			}
			updateFilesList();
			</script>

<div class="space"></div>
</div>

<div id="edycja_pliku" class="content_block" style="display:none">
			<div class="space"></div>
</div>

<div class="space"></div><br /><br />