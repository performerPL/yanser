
<div class="menu_group" style="width: 770px; margin: 20px auto; padding: 20px;">

<?php if(!defined('_APP')) exit;

_gui_header($T['loging_in']);

_gui_form_start('loginFrm','login.php');

	_gui_hidden('cmd','login');

	_gui_text('u',$T['Login'], '', false, true, $Error['u']);
	_gui_password('p',$T['Password'], '', false, true);
	echo '<div class="row_left"></div><div class="row_right"><input type="submit" style="margin: 20px 0 0 3px;" value="' . $T['log_in'] . '" class="btn" /></div>';

_gui_form_end();

?><script>$('u').focus();</script>

<div class="space"></div>
</div>