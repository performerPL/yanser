<?php
require_once('_header.php');
require_once('../lib/gallery.php');
require_once('../module/mod_contact_form.class.php');
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';
$module = new mod_contact_form();
$Tab = $module->getForms();
$Stats =  array(
	$T['form_count'] => count($Tab)
);
require_once('tpl/header.html.php');
require_once('tpl/index_contact_forms.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
