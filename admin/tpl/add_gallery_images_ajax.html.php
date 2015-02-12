<?php 
if (!defined('_APP')) {
  exit; 
}
?>
<div style="float: left;"><b>Lista galerii</b>:&nbsp;&nbsp;</div>
<div class="row_right2">
<?php
	$res = gallery_list();
	$first = true;
	foreach ($res as $tab) {
	    if ($first) {
	      $first = false;
	    } else {
	     echo ' | '; 
	    }
	    $style = '';
	    if ($_GET['list'] == $tab['gallery_id']) {
	      $style = 'class="link_gal_selected" '; 
	    }
		echo '<a ' . $style . 'style="cursor: pointer;" onclick="new Ajax.Updater(\'addbody\', \'add_gallery_images.php?cmd=list&list=' . $tab['gallery_id'] . '\');">';
		echo htmlspecialchars($tab['gallery_name']);
		echo '</a>';
	}
?>
| <a <?php if ($_GET['list'] == '/'): ?> class="link_gal_selected" <?php endif ?> style="cursor: pointer;" onclick="new Ajax.Updater('addbody', 'add_gallery_images.php?cmd=list&list=/');">Wszystkie obrazki</a>
| <a <?php if (empty($_GET['list'])): ?> class="link_gal_selected" <?php endif ?> style="cursor: pointer;" onclick="new Ajax.Updater('addbody', 'add_gallery_images.php?cmd=list&list=0');">Nieprzypisane obrazki</a>

</div>

<?php
	if (isset($images)) {
?>
<input type="hidden" name="list" value="<?=$_REQUEST['list']?>" />
<ul class="index_gallery">
<?php
	$cfg = $GL_CONF['IMAGES_FILES'];
	foreach ($images as $image) {
?>
	<li><label>
  		
		<img alt="<?=htmlspecialchars($image)?>" src="<?=htmlspecialchars($cfg["IMAGE_BASE_URL"] . $cfg["IMAGE_DIR_1"] . $image)?>" />
				<input type="checkbox" name="add_image[]" value="<?=htmlspecialchars($image)?>"/>
<hr style="clear:both">
		<span class="title"><?=htmlspecialchars($image)?></span>
	</label></li>
<?php
	}
?><br style="clear: both;"/>
</ul>


<?php } ?>

