<?php if(!defined('_APP')) exit;?>

<div class="oper">
	<a href="edit_contact_form.php?form_id=0#content" title="<?php _t('form_add'); ?>"><img src="img/icon_menu_add_m.gif" border="0" width="20" height="20" alt="" /><?php _t('form_add'); ?></a>
</div>

<div class="history">
	<img src="img/icon_menu.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<?php _t('contact_forms_mgmt'); ?>
</div>

<?php
if(isset($Message) && $Message!='') {
	?>
	<div class="message">
		<?php echo $Message; ?>
	</div>
	<?php
}
?>
<!--  index menu -->


<?php
_gui_stats($Stats);

if(count($Tab)>0) {
	?>
	<div class="content_block">
	<table class="data" cellspacing="1" width="100%">
	<tr>
		<th><a href="#" class="sortheader" onclick="ts_resortTable(this);return false;"><?php _t('Form_code'); ?><span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
		<th><?php _t('Form_name'); ?></th>
		<th>&nbsp;</th>
	</tr>
	<?php
	$x=  0;
	foreach($Tab as $k=>$v) {
		?>
		<tr class="data_row<?php echo intval(($x%2)+1);?>">
			<td><?php echo htmlspecialchars($v['form_type_id']); ?></td>
			<td><?php echo htmlspecialchars($v['form_type_name']); ?></td>
			<td><a name="i_<?php echo intval($k); ?>" href="edit_contact_form.php?form_id=<?php echo intval($v['form_type_id']); ?>#content" title="<?php _t('form_edit'); ?>"><img src="img/icon_menu_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('form_edit'); ?></a></td>
		</tr>
		<?php
		++$x;
	}
	?>
	</table>
	
<div class="space"></div><div id="global_btn">
	<?php _gui_button($T['ok'], 'location.href=\'index.php\''); ?>
</div>	
	</div>
	<!-- nawigacja po stronacch ewentualnie<div class="navbar">
	</div>-->
	<?php
} else {
	?>
	<p class="message">
	<?php _t('no_menu_msg'); ?>
	</p>
	<?php
}
?>
<!--  /index menu -->




<br />