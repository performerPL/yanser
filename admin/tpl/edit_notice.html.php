<?php if (!defined('_APP')) exit; ?>

  <?php if ($ID>0): ?>
  <div class="oper">
  </div>
  
  <?php endif ?>

<div class="history">
  <?php if ($ID>0): ?>
    <img src="img/icon_user_edit.gif" width="64" height="64" border="0" alt="" /> 
    <?php else: ?>
    <img src="img/icon_user_add.gif" width="64" height="64" border="0" alt="" /> 
    <?php endif ?>
  <a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
  <a href="index_notice_notice.php?user_id=<?php echo $TMP['n_user'];  echo $ID > 0 ? '#i_'.intval($ID):'#content'; ?>" title="<?php _t('user_mgmt'); ?>"><?php _t('user_mgmt'); ?></a>
  <?php _t('user_edit');  
  if (isset($Message) && $Message!=''):
  ?>
  <div class="message">
    <?php echo $Message; ?>
  </div>
  <?php endif ?>
</div>

<?
// pokazuje formularz do edycji
include_once 'class/Notice.class.php';
$Notice = new Notice();
$Notice->showEditForm($Tab);
?>

</div><br />