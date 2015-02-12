<?php 
if (!defined('_APP')) {
exit;
}
 ?>
</div>
</div>

<?php
if (_sec_logged()) {
require_once '../lib/user.php';
$U = get_users_online();
?>
<div id="footer_logged">
Użytkownicy online:<br/>
<?php foreach ($U as $V): ?>
<?php echo $V['user_name'] . ',' ?>
<?php endforeach ?>
</div>
<div id="footer">
<?php echo ADMIN_FOOTER_TEXT; ?>
</div>
<?php
}
if (_sec_logged()) {
	?>
	<script type="text/javascript">
	function refreshIt() 
	{
		if (!document.images) {
			return;
		}
		var id = Math.round(Math.random()*1000);
		document.getElementById('keepalive').src = '<?php echo ADMIN_PATH; ?>_keepalive.php/'+id+'/image.gif';
		setTimeout('refreshIt()',180000);
	}
	setTimeout('refreshIt()',180000);
	</script>
	<?php
}
?>
<div style="display:none"><img id="keepalive" name="keepalive" src="<?php echo ADMIN_PATH; ?>_keepalive.php" width="0" height="0" alt="" /></div>
</div></body>
</html>