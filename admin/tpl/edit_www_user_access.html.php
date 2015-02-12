<?php 
if (!defined('_APP')) {
  exit; 
}
?>

<?php if ($ID > 0): ?>
  <div class="oper">
    <a href="edit_www_user.php?wu_id=<?php echo intval($ID); ?>" title="<?php _t('user_edit'); ?>" ><img src="img/icon_user_access_m.gif" width="20" height="20" alt="" border="0" /><?php _t('user_edit'); ?></a>
    <a href="javascript:remove()" title="<?php _t('user_delete'); ?>" class="delete"><img src="img/icon_user_delete_m.gif" width="20" height="20" alt="" border="0" /><?php _t('user_delete'); ?></a>
    
  </div>
  
<?php endif ?>

<div class="history">
  <?php if ($ID > 0): ?>
    <img src="img/icon_user_edit.gif" width="64" height="64" border="0" alt="" /> 
    <?php else: ?>
    <img src="img/icon_user_add.gif" width="64" height="64" border="0" alt="" /> 
    <?php endif ?>
  <a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
  <a href="index_www_user.php<?php echo $ID>0?'#i_'.intval($ID):'#content'; ?>" title="<?php _t('user_www_mgmt'); ?>"><?php _t('user_www_mgmt'); ?></a>
  <?php _t('user_www_edit_access');   
  if (isset($Message) && $Message != ''):
  ?>
  <div class="message">
    <?php echo $Message; ?>
  </div>
  <?php endif ?>
</div>

<?php
if ($ID > 0) {
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
  _gui_form_start('deleteFrm', '', 'post', false);
  _gui_hidden('cmd', 'delete');
  _gui_hidden('user_id', intval($ID));
  _gui_form_end(false);
  /*_gui_stats(array(
    $T['id'] => $Tab['user_id'],
    $T['created'] => $Tab['created'],
    $T['last_login'] => $Tab['last_login'],
  )); */
} 

_gui_form_start('editFrm', 'edit_www_user_access.php');
  _gui_hidden('cmd', 'edit');
  _gui_hidden('wu_id', intval($ID));
    _gui_checkbox('wu_active', $T['user_active'], 1, $Tab['wu_active'] > 0, $Error['user_active']);
  
  _gui_break();
  foreach ($Tab['menu_list'] as $key => $menu) {
    $access=0;
    foreach ($Tab['menu_access'] as $key1 => $menu_access) {
      if ($access==1) {
        continue;
      }
      if ($menu_access['wug_id']==$menu['wug_id']) { 
        $access=1;
      }
    }
    _gui_checkbox('allow_menu_access['.$menu['wug_id'].']', 'Użytkownik nalezy do grupy: ' . $menu['wug_name'],1,$access,$Error['allow_upload']);
  }
  
  _gui_break();
echo '<div class="space"></div><div id="global_btn">';
    _gui_button($T['cancel'],'location.href=\'index_www_user.php'.($ID>0?'#i_'.intval($ID):'#content').'\'');
    _gui_button($T['ok'],'','editFrm');
echo '</div>';
_gui_form_end();
?>
</div><br />