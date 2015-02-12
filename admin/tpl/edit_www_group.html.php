<?php 
if (!defined('_APP')) {
  exit; 
}
?>

  <?php
  if ($ID > 0) {
  ?>
  <div class="oper">
  
  <?php if (_sec_authorised(ACCESS_MIN_ADMIN)): ?>
    <a href="javascript:remove()" title="<?php _t('user_delete'); ?>" class="delete"><img src="img/icon_user_delete_m.gif" width="20" height="20" alt="" border="0" /><?php _t('user_delete'); ?></a>
    <?php endif ?>
  
  
  </div>
  
  <?php } ?>

<div class="history">
  <?php
  if ($ID > 0) {
    ?>
    <img src="img/icon_user_edit.gif" width="64" height="64" border="0" alt="" /> 
    <?php
  } else {
    ?>
    <img src="img/icon_user_add.gif" width="64" height="64" border="0" alt="" /> 
    <?php
  }
  ?>
  <a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
  <a href="index_www_groups.php<?php echo $ID>0?'#i_'.intval($ID):'#content'; ?>" title="<?php _t('user_mgmt'); ?>"><?php _t('user_mgmt'); ?></a>
  <?php _t('user_edit');  
  if(isset($Message) && $Message!='') {
  ?>
  <div class="message">
    <?php echo $Message; ?>
  </div>
  <?php
}
  ?>
</div>

<?php
if($ID>0) {
  ?>

  <script type="text/javascript">
  function remove() {
    if(confirm('<?php addslashes(_t('user_delete_confirm')); ?>')) {
      document.deleteFrm.submit();
    }
  }
  </script>
  
  
  <div class="content_block">
  <?php
  _gui_form_start('deleteFrm','','post',false);
  _gui_hidden('cmd','delete');
  _gui_hidden('wug_id',intval($ID));
  _gui_form_end(false);

} 

_gui_form_start('editFrm','edit_www_group.php');
  _gui_hidden('cmd','edit');
  _gui_hidden('wug_id',intval($ID));
  
  _gui_break();
  _gui_text('wug_name','Nazwa grupy',$Tab['wug_name'],255,true,$Error['user_name']);
  function _digg_groups($Tab, $delim = '', $parent = 0)
  {
   foreach ($Tab['group_list'] as $k => $V) {
   if ($V['wug_parent_id'] != $parent) {
    continue; 
   }
   $checked = '';
   if ($Tab['wug_parent_id'] == $V['wug_id']) {
     $checked = 'checked="checked"';
   }
     echo '<option value=' . $V['wug_id'] . ' ' . $checked . '>' . $V['wug_name'] . '</td>';
     if ($parent != $V['wug_parent_id']) {
        _digg_groups($Tab, $delim . '-', $V['wug_parent_id']);
     }
   }
  }
  ?>
  <div class="row">
<div class="row_left">
Rodzic:
</div>
<div class="row_right">
<select name="wug_parent_id">
<option value="0">Brak</option>
<?php echo _digg_groups($Tab) ?>
</select>
</div>
</div>
  <?php
  _gui_break();
echo '<div class="space"></div><div id="global_btn">';
    _gui_button($T['cancel'],'location.href=\'index_www_groups.php'.($ID>0?'#i_'.intval($ID):'#content').'\'');
    _gui_button($T['ok'],'','editFrm');
echo '</div>';
_gui_form_end();


?>

</div><br />