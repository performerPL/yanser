<?php 
if (!defined('_APP')) {
  exit;
}
?>

<div class="history">
  <img src="img/icon_user.gif" width="64" height="64" border="0" alt="" /> 
  <a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
  <?php _t('user_mgmt'); ?>
</div>

<?php
if (isset($Message) && $Message != '') {
  ?>
  <div class="message">
    <?php echo $Message; ?>
  </div>
  <?php
}
?>



<div class="content_block">
<?php
//_gui_stats($Stats);

if (count($Tab) > 0) {
  ?>
  <table class="data">
  <tr>
    <th>Login</th>
    <th>Email</th>
    <th>Ilość ogłoszeń</th>
    <th>&nbsp;</th>
  </tr>
  <?php
  $x = 0;
  foreach ($Tab as $k => $v) {
    ?>
    <tr class="data_row<?php echo intval(($x%2)+1); echo $v['active']>0?'':' off'; ?>">
      <td><?php echo htmlspecialchars($v['wu_login']); ?></td>
      <td><?php echo htmlspecialchars($v['wu_email']); ?></td>
      <td><?php echo $v['count'] ?></td>
      <td><a name="i_<?php echo intval($k); ?>" href="index_notice_notice.php?user_id=<?php echo intval($v['wu_id']); ?>#content" title="<?php _t('user_edit'); ?>"><img src="img/icon_user_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('user_edit'); ?></a></td>
    </tr>
    <?php
    ++$x;
  }
  ?>
  </table>
  <!-- nawigacja po stronacch ewentualnie<div class="navbar">
  </div>-->
  <div class="space"></div><div id="global_btn">
  <?php _gui_button($T['ok'], 'location.href=\'index.php\''); ?>
</div>
  </div><br />
  <?php
} else {
  ?>
  <p class="message">
  <?php _t('no_users_msg'); ?>
  </p>
  <?php
}
?>

