<?php 
if (!defined('_APP')) {
  exit;
}
?>

<div class="oper">
  <a href="edit_www_user.php?user_id=0#content" title="<?php _t('user_add'); ?>"><img src="img/icon_user_add_m.gif" border="0" width="20" height="20" alt="" /><?php _t('user_add'); ?></a>
  <a href="export_www_user.php?type=xls" title=""><img src="img/xls.gif" border="0" width="20" height="20" alt="" /><?php echo 'Export danych - Excel' ?></a>
</div>

<div class="history">
  <img src="img/icon_user.gif" width="64" height="64" border="0" alt="" /> 
  <a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
  <?php _t('user_www_mgmt'); ?>
</div>

<?php if (isset($Message) && $Message != ''):?>
  <div class="message">
    <?php echo $Message; ?>
  </div>
  <?php endif ?>
<form method="post">
<input type="hidden" name="cmd" value="updateactive"/>
<div class="content_block">
<?php if (count($Tab) > 0): ?>
  <table class="data" width="100%">
  <tr>
    <th>Login</th>
    <th>Email</th>
    <th>Newsletter</th>
    <th>Data rejestracji</th>
    <th>Aktywny</th>
<?php if (_sec_authorised(ACCESS_MIN_ADMIN)): ?><th>&nbsp;</th><?php endif ?>
    <th>&nbsp;</th>
  </tr>
  <?php
  $x = 0;
  foreach ($Tab as $k => $v):
  ?>
    <tr class="data_row<?php echo intval(($x%2)+1); echo $v['wu_active'] > 0 ? '' : ' off'; ?>">
      <td><?php echo htmlspecialchars($v['wu_login']); ?></td>
      <td><?php echo htmlspecialchars($v['wu_email']); ?></td>
      <td><?php if($v['wu_newsletter'] == 1) echo "Tak"; ?></td>
      <td><?php echo htmlspecialchars($v['wu_created']); ?></td>
      <td><input type="checkbox" name="actives[<?php echo $v['wu_id'] ?>]" value="1"<?php if ($v['wu_active'] == 1): ?> checked="checked"<?php endif ?>/></td>
<?php if (_sec_authorised(ACCESS_MIN_ADMIN)): ?><td><a href="edit_www_user_access.php?wu_id=<?php echo intval($k); ?>#content" title="<?php _t('user_access'); ?>"><img src="img/icon_user_access_m.gif" border="0" width="20" height="20" alt="" /><?php _t('Access'); ?></a></td><?php endif ?>
      <td><a name="i_<?php echo intval($k); ?>" href="edit_www_user.php?wu_id=<?php echo intval($k); ?>#content" title="<?php _t('user_edit'); ?>"><img src="img/icon_user_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('user_edit'); ?></a></td>
    </tr>
    <?php
    ++$x;
  endforeach;
  ?>
  </table>
  <!-- nawigacja po stronacch ewentualnie<div class="navbar">
  </div>-->
  <div class="space"></div><div id="global_btn">
  <input class="btn" type="submit" value=" OK "/>
</div>
</form>
  </div><br />
  <?php 
    // wyświetla stronnicowanie
    $UserWWW->getPaging($paging,$show);
    
  else: ?>
  <p class="message">
  <?php _t('no_users_msg'); ?>
  </p>
  <?php endif ?>

