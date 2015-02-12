<?php 
if (!defined('_APP')) {
  exit;
}
?>

<div class="history">
  <img src="img/icon_user.gif" width="64" height="64" border="0" alt="" /> 
  <a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
  <?php _t('user_www_changes'); ?>
</div>

<?php if (isset($Message) && $Message != ''):?>
  <div class="message">
    <?php echo $Message; ?>
  </div>
  <?php endif ?>
<div class="content_block">
<?php if (count($Tab) > 0): ?>
  <table class="data">
  <tr>
    <th>Kto?</th>
    <th>Kogo?</th>
    <th>Data</th>
    <th>Akcja</th>
  </tr>
  <?php
  $x = 0;
  foreach ($Tab as $k => $v):
  ?>
    <tr class="data_row">
      <td><?php echo htmlspecialchars($v['wuh_login']); ?></td>
      <td><?php echo htmlspecialchars($v['wuh_who']); ?></td>
      <td><?php echo htmlspecialchars($v['wuh_date']); ?></td>
      <td><?php echo htmlspecialchars($v['wuh_what']); ?></td>
    </tr>
    <?php
    ++$x;
  endforeach;
  ?>
  </table>
  <!-- nawigacja po stronacch ewentualnie<div class="navbar">
  </div>-->
  <div class="space"></div>  </div><br />
  <?php else: ?>
  <p class="message">
  <?php _t('no_users_msg'); ?>
  </p>
  <?php endif ?>

