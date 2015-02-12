<?php 
if (!defined('_APP')) {
  exit; 
}
?>
<html>
<head>
	<?php 
		require_once 'header_inner.html.php';
	?>
	
	<link rel="stylesheet" href="<?php echo ADMIN_PATH; ?>subModal.css" type="text/css" media="screen">
	<link rel="stylesheet" href="<?php echo ADMIN_PATH; ?>tab-view.css" type="text/css" media="screen">

	<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>js/subModal.js"></script>
	<script type="text/javascript" src="<?php echo ADMIN_PATH; ?>js/tab-view.js"></script>
</head>
<body>
<div id="page">
	<div id="header">
		<div id="logo"  style="display:none"><a href="index.php"><!--<img src="img/logo.gif" width="190" height="45" alt="Performer CMS" border="0" /> -->Content Managemet System</a></div>
		<?php
		if (_sec_logged()) {
			?>
			<div id="head_links">
<?php /*				<a href="<?php echo ADMIN_HELP_LINK ?>" title="<?php _t('Help'); ?>" target="blank"><img src="img/icon_help.gif" width="22" height="22" border="0" alt="" /><?php _t('Help'); ?></a>*/?>
<a href="edit_user.php?user_id=<?=$_SESSION['cms_logged_user']['user_id'] ?>#content" title="Edytuj"><img src="img/icon_edit.gif" width="20" height="20" border="0" alt="" />Edytuj</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="logout.php" title="<?php _t('Logout'); ?>"><img src="img/icon_logout.gif" width="20" height="20" border="0" alt="" /><?php _t('Logout'); ?></a>
			</div>
			<div class="info">
				<b><?php echo _sec_user('user_name'); ?></b> | <?php echo htmlspecialchars($GL_ACCESS_LVL[_sec_user('access_level')]); ?><br />
				<?php _t('last_login'); echo ' '._sec_user('last_login'); ?>
			</div>
			<?php
		}
		?>
		
		<div id="search"  style="display:none">
	<form action="search.php" method="post" name="searchFrm">
	<input type="text" class="in" style="width: 150px;"/><a href="javascript:searchFrm.submit()"><img src="img/icon_search.gif" width="30" height="30" border="0" alt="<?php _t('search'); ?>" /></a>
	</form>
</div>
		
		
	</div>

	<div id="content_out"><div id="content">
	