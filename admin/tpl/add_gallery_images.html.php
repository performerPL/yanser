<?php
if (!defined('_APP')) {
  exit; 
}
require_once '_header.php';
?>
<form method="POST" name='editFrm2' id='editFrm2'>
	<input type="hidden" name="cmd" value="add" />
	<input type="hidden" name="gallery_id" value="<?=htmlspecialchars($ID)?>" />

<div id="addbody">
</div>	
<div class="space"></div><div id="global_btn">
<input class="btn" type="button" value="Anuluj" onclick="cancel_i()"/>
<input class="btn" type="button" value=" OK " onclick="document.editFrm2.submit()"/>
</div>
</form>
