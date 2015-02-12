<?php 
if (!defined('_APP')) {
  exit; 
}
require_once '_header.php';
?>
<form method="POST" name='editFrm2' id='editFrm2'>
	<input type="hidden" name="cmd" value="add2" />
	<input type="hidden" name="group_id" value="<?=htmlspecialchars($ID)?>" />

<div id="addbody">
</div>	
<div style="text-align:center">
<?php
_gui_button($T['cancel'],'cancel_i()');
_gui_button($T['ok'],'','editFrm2');
?>
</div>
</form>