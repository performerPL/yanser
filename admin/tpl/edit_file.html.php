<?php if (!defined('_APP')) exit; ?>
<?php if ($SHOWLINE): ?>
<img src="img/icon_mod_gallery_m.gif" width="16" height="16" class="mod_icon" />

<span class="file_name">
 <?php if ($file[0]['file_type'] == 'directory') {
				?><a href="?group_id=<?=$_GET['group_id']?>&dir=<?=$_GET['direk']?>.'/'.$file[0]['file_name']?>"><?=htmlspecialchars($file[0]['file_name'])?></a>&nbsp;</span><?
			} else {?>
<?=$file[0]['file_name']?>
<?php } ?>
</span>

<span class="file_title"><?=$file[0]['file_title']?></span>
<span class="file_description"><?=$file[0]['file_description']?></span>
<span class="fole_tools">
	<a href="javascript:void(0)" onclick="showForma(<?=$file[0]['file_id']?>, <?=$_GET['group_id']?>)" ><? _t("mod_ftp_file_edit") ?></a> | 
		 <?php if ($file[0]['file_type'] == 'directory')
			{?><a href="edit_group.php?cmd=delete&file_id=<?=$file[0]['file_id']?>&file_type=directory&file_name=<?=$file[0]['file_name']?>&group_id=<?=$_GET['group_id']?>"><? _t("mod_ftp_file_delete") ?></a><?} else {?><a href="edit_group.php?cmd=delete&file_id=<?=$file[0]['file_id']?>&file_type=file&file_name=<?=$file[0]['file_name']?>&group_id=<?=$_GET['group_id']?>&file_dir=<?=$_GET['direk']?>"><? _t("mod_ftp_file_delete") ?></a><?}?>	
	| <a href="edit_group.php?cmd=delete_filegroup&file_id=<?=$file[0]['file_id']?>&group_id=<?=$_GET['group_id']?>"><? _t("mod_ftp_file_group_delete") ?></a>
			</span>

</form>
<?php else: ?>
<form name="editFileFrm_<?=$file[0]['file_id']?>" id="editFileFrm_<?=$file[0]['file_id']?>" method="POST" action="" enctype="multipart/form-data">
<img src="img/icon_mod_gallery_m.gif" width="16" height="16" class="mod_icon" />

<span class="file_name"><?=$file[0]['file_name']?></span>

<span class="file_title"><input type="text" name="file_title" value="<?=$file[0]['file_title']?>"/> </span>
<span class="file_description"><textarea name="file_description"><?=$file[0]['file_description']?> </textarea></span>
<span class="fole_tools">
	<input type="hidden" name="file_id" value="<?=$_GET['file_id']?>"/>
	<input type="hidden" name="cmd" value="edit_file"/>
	<a href="javascript:void(0)" onclick="anulujForma(<?php echo $file[0]['file_id'] ?>, <?=$_GET['group_id']?>)"><?php echo $T['cancel'] ?></a>
	<a href="javascript:void(0)" onclick="submitForma(<?php echo $file[0]['file_id'] ?>, <?=$_GET['group_id']?>)"><?php echo $T['ok'] ?></a>
			</span>

</form>
<?php endif ?>