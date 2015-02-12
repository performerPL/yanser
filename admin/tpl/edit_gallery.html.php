<?php 
if (!defined('_APP')) {
  exit; 
}
?>	
<div class="oper">
		<a href="javascript:remove()" title="<?php _t('gallery_delete'); ?>" class="delete"><img src="img/icon_gallery_delete_m.gif" width="20" height="20" alt="" border="0" /><? _t('gallery_delete'); ?></a>
</div>
<div class="history">
	<img src="img/icon_gallery_edit.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<a href="index_gallery.php#content" title="<?php _t('gallery_mgmt'); ?>"><?php _t('gallery_mgmt'); ?></a>
	<?php 
	_t('gallery_edit'); 	
	if (isset($Message) && $Message != ''):
		?>
		<div class="message">
			<?=$Message ?>
		</div>
		<?php endif	?>
</div>

<div class="content_block">
	<script type="text/javascript">
	function remove() 
	{
		if (confirm('<?php addslashes(_t('gallery_delete_confirm')); ?>')) {
			document.deleteFrm.submit();
		}
	}
	</script>
	<?php
	_gui_form_start('deleteFrm', '', 'post', false);
	_gui_hidden('cmd', 'delete');
	_gui_hidden('gallery_id', intval($ID));
	_gui_form_end(false);
	//_gui_stats(array( $T['id'] => $Tab['gallery_id'], ));
	?>

<?php
	_gui_form_start('editFrm','','post');
	_gui_hidden('cmd','edit');
	_gui_hidden('gallery_id', $Tab['gallery_id']);
	_gui_text('gallery_name', $T['gallery_name'], $Tab['gallery_name'], 120, true, $Error['gallery_name']);
	_gui_textarea('gallery_description', $T['gallery_description'], $Tab['gallery_description'], 30, 5, WYSIWYG_SIMPLE, false, '', $T['gallery_description_info']);
	_gui_checkbox('show_voting',$T['gallery_show_voting'],1,$Tab['show_voting']>0,'',$T['gallery_show_voting_info']);
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
	
	// dostep do serwisow end

	echo '&nbsp;';

	
	echo '<div class="space"></div><div id="global_btn">';
		_gui_button($T['cancel'],'location.href=\'index_gallery.php#content\'');
		_gui_button($T['ok'],'','editFrm');
	echo '</div>';	

_gui_form_end();


?>
<div class="space"></div>
</div>


<br /><br />

<script type="text/javascript">

function load_i(url, galeria) 
{
 var x = new Ajax.Updater("upload_photo", url, {
    			method: "get",
    			onComplete: function(){ 
    				//document.getElementById('addImageFrm').action = url;
    				if (galeria == true) {
    					new Ajax.Updater('addbody', 'add_gallery_images.php?cmd=list&list=');
    					document.getElementById('editFrm2').action = url;
    				}
    			}
    			});
   document.getElementById('upload_photo').style.display='block';
}

function cancel_i()
{
document.getElementById('upload_photo').style.display='none';
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

function image_exists(filename) {
    jQuery.post("edit_gallery.php", { cmd: "image_exists", ajax: "1", filename: filename },
            function(data){
                if(data == '1') {
                   jQuery('#picture_file_error').html('Obrazek o nazwie "'+filename+'" już istnieje w systemie. <br> Po zapisie zostanie on nadpisany przez nowy plik, zatem jeżeli tego nie chcesz zmien nazwę pliku na dysku.');
                }
                else {
                	jQuery('#picture_file_error').html('');
                }
    });
}

</script>

<div class="space"></div>
<div class="content_block" style="background: #f4faff">
	
		<input type="hidden" id="type" value="list" />
		<a onclick="$('type').value = 'list'; updatePicturesList();" style="float:left; padding: 8px 0 0px  20px;"><?php _t("picture_list"); ?></a>
		<a onclick="$('type').value = 'thumbnails'; updatePicturesList();" style="float:left; padding: 8px 0 0px  40px;"><?php _t("picture_thumbnails"); ?></a>
	
	
		<a name="gallery" href="#gallery" onclick="load_i('add_gallery_image.php?gallery_id=<?=$ID ?>', false)" title="<?php _t('gallery_add_image'); ?>" class="add_image" style="float:right; padding: 5px 40px 5px 0;"><img src="img/icon_gallery_add_image_m.gif" width="20" height="20" alt="" border="0" />&nbsp;&nbsp;<?php _t('gallery_add_image'); ?></a>
		<a name="gallery" href="#gallery" onclick="load_i('add_gallery_images.php?gallery_id=<?=$ID ?>', true)" title="<?php _t('gallery_add_images'); ?>" class="add_image" style="float:right; padding: 5px 40px 5px 0;"><img src="img/icon_gallery_add_images_m.gif" width="20" height="20" alt="" border="0" />&nbsp;&nbsp;<?php _t('gallery_add_images'); ?></a>
<div class="space"></div>
		</div>

<div class="content_block" style="display:none" id="upload_photo">

</div>	
	
<div class="content_block">

<div id="galleryPictures">

<div class="space"></div>
</div>
<script>
function updatePicturesList() {
	new Ajax.Updater('galleryPictures', 'list_gallery.php?type=' + $('type').value + '&gallery_id=<?=intval($ID)?>', { evalScripts: true });
}
updatePicturesList();
</script>

<div class="space"></div>
</div><br />