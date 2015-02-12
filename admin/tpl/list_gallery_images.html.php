<?php 
if (!defined('_APP')) {
  exit; 
}
?>
<div class="row_left">Wybierz galerię:</div>
<div class="row_right">
<?php

$gallery_list = gallery_get_all();
$first = true;
foreach ($gallery_list as $gallery_item) {
  if ($first) {
    $first = false;
  } else {
   echo '  '; 
  }
	?>
		<a<?php if ($_GET['gallery_id'] == $gallery_item['gallery_id']): ?> class="link_gal_selected" <?php endif ?> href="#" onclick="load_u('list_gallery_images.php?gallery_id=<?=$gallery_item['gallery_id']?>&field=<?=$_GET['field']?>&type=<?=$_GET['type']?>')"><?=$gallery_item['gallery_name']?></a> 
	<?php
}
?>

</div>
<div class="row2">
<?php
if(isset($_GET['gallery_id']) && $_GET['gallery_id']!=''){
?>
<ul class="index_gallery">
<?php

$images = gallery_images_files($_GET['gallery_id'],0,true);
  foreach($images as $image) {
?>
 		<li>
				<div class="index_picture">
				<a href="#" onclick="document.getElementById('<?php echo addslashes($_REQUEST["field"]); ?>').value='<?=addslashes($GL_CONF["IMAGES_FILES"]["IMAGE_DIR_1"] . $image[picture_file])?>'; unload_u();">
				<img src="<?=htmlspecialchars($GL_CONF["IMAGES_FILES"]["IMAGE_BASE_URL"] . $GL_CONF["IMAGES_FILES"]["IMAGE_DIR_1"] . $image[picture_file])?>" border="0"/>
				</div>
				<?
				if ($image[picture_title]) {
					echo '<span class="title">'.htmlspecialchars($image[picture_title]).'</span>';
				} else {
					echo '<span class="title" style="">'.htmlspecialchars($image[picture_file]).'</span>';
				}
				?>
				
		</li>
<?php
  }
  ?>
  <div style="clear: both"></div>
</ul>
  <?php
}
?>
</div>

<?php if(isset($_GET['gallery_id']) && $_GET['gallery_id']!=''){?>
<div class="row2">
</div>
<?php } ?>
<div class="row_anuluj">
<?php _gui_button($T['cancel'],'unload_u()'); ?>
</div>