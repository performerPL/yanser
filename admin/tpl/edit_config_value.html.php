<?php
if (!defined('_APP')) {
  exit;
}
?>
<div class="history"><?php
if ($Conf['config_icon'] == '') {
  if ($ID > 0) {
    ?> <img src="img/icon_config_value.gif" width="64" height="64"
	border="0" alt="" /> <?php
} else {
  ?> <img src="img/icon_config_value_add.gif" width="64" height="64"
	border="0" alt="" /> <?php
}
} else {
  ?> <img src="<?php echo htmlspecialchars($Conf['config_icon']); ?>"
	border="0" alt="" /> <?php
}
?> <a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
<a href="index_config_value.php<?php echo '#i_'.intval($ConfID); ?>"
	title="<?php _t('config_mgmt'); ?>"><?php _t('config_mgmt'); ?></a> <?php 
	echo htmlspecialchars($Conf['config_name']);
	if (isset($Message) && $Message != '') {
	  ?>
<div class="message"><?php echo $Message; ?></div>
	  <?php
}
?></div>

<?php
if ($ID>0 && $Conf['multiple'] > 0) {
  ?>
<div class="oper"><a href="javascript:remove()"
	title="<?php _t('config_value_delete'); ?>" class="delete"><img
	src="img/icon_config_value_delete_m.gif" width="20" height="20" alt=""
	border="0" /><?php _t('config_value_delete'); ?></a></div>
<script type="text/javascript">
	function remove() {
		if(confirm('<?php addslashes(_t('config_value_delete_confirm')); ?>')) {
			document.deleteFrm.submit();
		}
	}
	</script>
  <?php
  _gui_form_start('deleteFrm','','post',false);
  _gui_hidden('cmd','delete');
  _gui_hidden('value_id',intval($ID));
  _gui_hidden('config_id',intval($ConfID));
  _gui_form_end(false);
}

_gui_form_start('editFrm','edit_config_value.php');
_gui_hidden('cmd','edit');
_gui_hidden('config_id',intval($ConfID));
_gui_hidden('value_id',intval($ID));

/*
 _gui_text('config_code',$T['config_code'],$Tab['config_code'],120,true,$Error['config_code']);
 _gui_checkbox('is_group',$T['config_is_group'],1,$Tab['is_group']>0,$Error['is_group']);
 _gui_checkbox('multiple',$T['config_multiple'],1,$Tab['multiple']>0,$Error['multiple']);
 _gui_select('parent_id',$T['config_parent_id'],$Tab['parent_id'],$ConfigParents,'','config_parent_txt_func',false,'',$T['config_parent_info']);
 function config_parent_txt_func($k,$v) {
 if(is_array($v)) {
 return $v['config_name'].' - '.$v['config_code'];
 } else {
 return $v;
 }
 }
 _gui_text('config_regex',$T['config_regex'],$Tab['config_regex'],255,false,'',$T['config_regex_info']);
 _gui_checkbox('allow_edit',$T['config_allow_edit'],1,$Tab['allow_edit']>0,$Error['allow_edit']);
 _gui_break();
 _gui_text('config_name',$T['config_name'],$Tab['config_name'],255,true,$Error['config_name']);
 _gui_text('config_icon',$T['config_icon'],$Tab['config_icon'],255);
 _gui_textarea('info',$T['config_info'],$Tab['info']);
 */
if ($Conf['is_group']) {
  if ($Conf['multiple']) {
    _gui_text($Conf['config_code'],$T['config_value_id'].':',$Tab[$Conf['config_code']],255,true,$Error[$Conf['config_code']],$T['config_value_info']);
    _gui_break();
  }

  foreach ($Conf['subconfig'] as $k => $v) {
    _gui_text($v['config_code'],$v['config_name'].':',$Tab[$v['config_code']],255,$v['config_regex']!='',$Error[$v['config_code']],$v['info']);
  }
} else {
  _gui_text($Conf['config_code'],$Conf['config_name'].':',$Tab[$Conf['config_code']],255,$Conf['config_regex']!='',$Error[$Conf['config_code']],$Conf['info']);
}

_gui_break();
_gui_form_row();
echo '&nbsp;';
_gui_form_row_mid();
_gui_button($T['cancel'],'location.href=\'index_config_value.php#i_'.intval($ConfID).'\'');
_gui_button($T['ok'],'','editFrm');
_gui_form_row_end();
_gui_form_end();
