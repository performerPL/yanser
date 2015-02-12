<?php 
if (!defined('_APP')) {
  exit; 
}
?>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<title><?php echo ADMIN_TITLE; ?></title>
<link href="<?php echo ADMIN_PATH; ?>style.css" rel="stylesheet" type="text/css" />

<?php 	
// gdy zalogowany, załącza wymagane biblioteki
if (_sec_logged()) {
?>	
<link rel="stylesheet" href="<?php echo ADMIN_PATH; ?>tool/calendar/calendar-system.css" type="text/css" media="screen">
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>js/jquery/jquery.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>js/prototype.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>js/prototype-ext.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>js/scriptaculous.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>js/ajaxtree.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>js/common.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>js/commonajax.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>js/ajax.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>tool/calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>tool/calendar/calendar-setup.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>tool/calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>tool/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>js/lib.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>js/marcin.js"></script>
<?php }?>