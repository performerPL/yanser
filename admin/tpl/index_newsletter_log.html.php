<?php 
if (!defined('_APP')) {
  exit;
}
?>

<div class="oper">
</div>

<div class="history">
  <img src="img/icon_newsletter.gif" width="64" height="64" border="0" alt="" /> 
  <a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
  <?php _t('newsletter_mgmt'); ?>
</div>

<?php if (isset($Message) && $Message != ''):?>
  <div class="message">
    <?php echo $Message; ?>
  </div>
  <?php endif ?>
<form method="post">
<input type="hidden" name="cmd" value="updateactive"/>

<div class="content_block">
  <table class="data" width="100%">
  <tr>
    <th><?php echo $T['newsletter_date_send']?></th>
  </tr>
  <?php
  foreach ($Tab as $k => $v):
  ?>
    <tr class="data_row">
      <td><?php echo htmlspecialchars($v['datetime_send']); ?></td>
    </tr>
    <?php
  endforeach;
  ?>
  </table>
</div>
</form>

