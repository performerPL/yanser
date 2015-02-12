<?php

// lista z typem targetu
$imageTargetList = array(
"self" => "self",
"blank" => "blank"
);

$cfg = $GL_CONF['IMAGES_FILES'];
_gui_image('image_path', $T['mod_image_image_path'],$Tab['image_path'], false, false, false, $T['mod_image_image_path_info']);
$images = gallery_readdir();

_gui_form_row();
echo 'Obrazek:';
_gui_form_row_mid();
echo '<img  src="' . htmlspecialchars((ereg('https?://', $Tab['image_path']) ? '' : $cfg["IMAGE_BASE_URL"]) . $Tab['image_path']) . '" id="image" />';
_gui_form_row_end();

// podpis pod obrazkiem  
_gui_textarea('image_description',$T['mod_image_image_description'],$Tab['image_description'],30,5,WYSIWYG_SIMPLE, false,'',$T['mod_image_image_description_info']);
// scieżka do przekierowania po kliknieciu
_gui_text('image_target_url', $T['picture_target_url'], $Tab['image_target_url'], 120, false, $Error['image_target_url']);
// typ przekierowania
_gui_select('image_target', $T['picture_target'], $Tab['image_target'], $imageTargetList);
// zdjecie z powiekszeniem
_gui_checkbox('show_enlarge', $T['mod_gallery_show_enlarge_lightbox'], 1, $Tab['show_enlarge'] > 0);

?>
         <div class="row">
           <div class="row_left">Typ obrazka:</div>
           <div class="row_right">
         		<label><input type="radio" name="image_type" value="<?=htmlspecialchars($cfg["IMAGE_DIR_1"]) ?>" <?php if ($Tab['image_type'] == htmlspecialchars($cfg["IMAGE_DIR_1"]) || empty($Tab['image_type'])): ?>checked="checked"<?php endif ?>>małe</label>
         		<label><input type="radio" name="image_type" value="<?=htmlspecialchars($cfg["IMAGE_DIR_2"]) ?>" <?php if ($Tab['image_type'] == htmlspecialchars($cfg["IMAGE_DIR_2"])): ?>checked="checked"<?php endif ?>>średnie</label>
         		<label><input type="radio" name="image_type" value="<?=htmlspecialchars($cfg["IMAGE_DIR_3"]) ?>" <?php if ($Tab['image_type'] == htmlspecialchars($cfg["IMAGE_DIR_3"])): ?>checked="checked"<?php endif ?>>duże</label>
         		<label><input type="radio" name="image_type" value="<?=htmlspecialchars('gallery/orig/') ?>" <?php if ($Tab['image_type'] == htmlspecialchars('gallery/orig/')): ?>checked="checked"<?php endif ?>>oryginalne</label>
           </div>
           </div>
         <!-- ul class="index_gallery">
         <?php foreach ($images as $image):?>
           <li>
             <a href="#" onclick="img = $(document.editFrm).getInputs('radio','image_type').find(function(radio) { return radio.checked; }).value + '<?=addslashes($image)?>'; $('image_path').value = img; $('image').src = '<?=addslashes($cfg["IMAGE_BASE_URL"])?>' + img;">
               <img src="<?=htmlspecialchars($cfg["IMAGE_BASE_URL"] . $cfg["IMAGE_DIR_1"] . $image)?>" />
         			<hr style="clear:both">
               <span class="title"><?=htmlspecialchars($image)?></span>
           </li>
         <?php endforeach ?>
         <div style="clear: both"></div>
         </ul -->









         <div class="row">
               <div class="row_left">Wybierz galerię:</div>
                <div class="row_right">
                <!--  lista galerii -->
                <?php

                $gallery_list = gallery_get_all();
                $first = true;
                foreach ($gallery_list as $gallery_item) {
                	if (!$first) {
                	  echo ' | ';
                	} else {
                	  $first = false;
                	}
                	?> <a<?php if ($_GET['gallery_id'] == $gallery_item['gallery_id']): ?> class="link_gal_selected" <?php endif ?> href="javascript:void(0)" onclick="popup_url('edit_module.php?gallery_id=<?=$gallery_item['gallery_id']?>&article_id=<?=$_GET['article_id']?>&module_id=<?=$_GET['module_id']?>&module_type=<?=$_GET['module_type']?>')"><?=$gallery_item['gallery_name']?></a> 
                	<?php
                }
                ?>
                </div>
         </div>














<div class="row">
<?php
if (isset($_GET['gallery_id']) && $_GET['gallery_id'] != '') {
  $namei = gallery_get($_GET['gallery_id']);
?>










         <ul class="index_gallery" id="galeryjka">
         <?php

         $images = gallery_images_files($_GET['gallery_id'],0,true);

           foreach ($images as $image):
         ?>
          <li>
         		<div class="index_picture">
             <a href="#" onclick="img = $('editFrm').getInputs('radio','image_type').find(function(radio) { return radio.checked; }).value + '<?=addslashes($image[picture_file])?>'; $('image_path').value = img; $('image').src = '<?=addslashes($cfg["IMAGE_BASE_URL"])?>' + img; document.getElementById('galeryjka').style.display='none'">
               <img src="<?=htmlspecialchars($cfg["IMAGE_BASE_URL"] . $cfg["IMAGE_DIR_1"] . $image[picture_file])?>" border="0"/>
               </a>
         			</div>
               <span class="title"><?=htmlspecialchars($image[picture_file])?></span>
               <br/><br/>
               <span class="title"><?=htmlspecialchars($image[picture_title])?></span>
           </li>
         <?php endforeach ?>
         </ul>
         <div style="clear: both;">&nbsp;</div>
  <?php
}
?>
</div>

<!-- --------------------------------------------------------  -->




<?php 
if (isset($_GET['gallery_id']) && $_GET['gallery_id'] != '') {

      echo '<div class="row"></div>';
 } ?>
