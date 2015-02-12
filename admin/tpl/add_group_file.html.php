<?php 
if (!defined('_APP')) {
  exit; 
}
?>
<div class="uploadForm">
<form name="editFrm2" id="editFrm2" method="POST" action="" enctype="multipart/form-data">
<table>
	<tr>
		<td colspan=2><input type="file" name="file_name" /></td>
	</tr>
	<tr>
		<td><?php _t('mod_ftp_title'); ?></td>
		<td><input type="text" name="file_title" /></td>
	</tr>
	<tr>
		<td><?php _t('mod_ftp_description'); ?></td>
		<td><textarea name="file_description"> </textarea></td>
	</tr>
	<input type="hidden" name="dir_curr" value="<?echo $dir?>"/>
	<input type="hidden" name="group_id" value="<?=$ID?>"/>
	<input type="hidden" name="cmd" value="add"/>
	<tr><td>
			<?php
			_gui_button($T['cancel'],'cancel_i()');
			_gui_button($T['ok'],'','editFrm2');
			?>
		</td></tr>
</table>
</form>
</div>
