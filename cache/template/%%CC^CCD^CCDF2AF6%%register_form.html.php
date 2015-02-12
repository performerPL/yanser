<?php /* Smarty version 2.6.18, created on 2011-12-21 23:35:47
         compiled from mod_registeruser/register_form.html */ ?>
<div class="form_registeruser">	
<form method="post">
<div class="form_left">Nazwa użytkownika *: 
<?php if (( in_array ( 'login' , $this->_tpl_vars['out']['errors'] ) )): ?>
taki użytkownik już istnieje
<?php endif; ?>
</div>
<div class="form_right">
<input
	class="input<?php if (( in_array ( 'wu_login' , $this->_tpl_vars['out']['errors'] ) )): ?> red_error<?php endif; ?>"
	type="text" value="<?php echo $this->_tpl_vars['out']['user']['wu_login']; ?>
"
	name="USER[wu_login]"></div>
<div class="space"></div>

<div class="form_left">*Email :</div>
<div class="form_right">
<input
	class="input<?php if (( in_array ( 'wu_email' , $this->_tpl_vars['out']['errors'] ) )): ?>red_error<?php endif; ?>"
	type="text" value="<?php echo $this->_tpl_vars['out']['user']['wu_email']; ?>
"
	name="USER[wu_email]"></div>
<div class="space"></div>


<div class="form_left">Hasło *: 
<?php if (( in_array ( 'pass' , $this->_tpl_vars['out']['errors'] ) )): ?>
hasła nie zgadzają się
<?php endif; ?></div>
<div class="form_right"><input
	class="input<?php if (( in_array ( 'wu_password' , $this->_tpl_vars['out']['errors'] ) )): ?>red_error<?php endif; ?>"
	type="password" value="<?php echo $this->_tpl_vars['out']['user']['wu_password']; ?>
"
	name="USER[wu_password]"></div>
<div class="space"></div>

<div class="form_left">Potwierdź hasło *:</div>
<div class="form_right">
<input
	class="input<?php if (( in_array ( 'wu_password2' , $this->_tpl_vars['out']['errors'] ) )): ?>red_error<?php endif; ?>"
	type="password" value="<?php echo $this->_tpl_vars['out']['user']['wu_password2']; ?>
"
	name="USER[wu_password2]"></div>
<div class="space"></div>




<div class="form_left">Wpisz tekst z obrazka:<img src="secretImage.php" /> 
<?php if (( in_array ( 'captcha' , $this->_tpl_vars['out']['errors'] ) )): ?>
Podany kod z obrazka był nieprawidłowy. Proszę poprawnie podać aktualny kod z obrazka.
<?php endif; ?></div>
<div class="form_right" style="width: 145px;"><input class="input" type="text"name="captcha"></div>
<div class="space"></div>

<div class="form_left"></div>
<div class="form_right"><input type="hidden" name="i_cmd"
	value="<?php echo $this->_tpl_vars['out']['formType']; ?>
" /><input type="submit" value="Zapisz" class="btn"></div>
<div class="space"></div>
</form>
</div>