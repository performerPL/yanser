<?php 
if (!defined('_APP')) {
  exit;
}
?>

<div class="oper">
  <a href="edit_newsletter.php?newsletter_id=0#content" title="<?php _t('newsletter_add'); ?>"><img src="img/icon_newsletter_add_m.gif" border="0" width="20" height="20" alt="" /><?php _t('newsletter_add'); ?></a>
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

<?php if (count($Tab) > 0): ?>
<div class="content_block">
  <table class="data" width="100%">
  <tr>
    <th><?php echo $T['newsletter_title']?></th>
    <th><?php echo $T['newsletter_type']?></th>
    <th style="width: 80px;"><?php echo $T['newsletter_date_send']?></th>
    <th><?php echo $T['newsletter_date_last_send']?></th>
    <th><?php echo $T['newsletter_day_loop']?></th>
    <th><?php echo $T['newsletter_active']?></th>
    <th><?php echo $T['newsletter_groups']?></th>
    <th>&nbsp;</th>
  </tr>
  <?php
  $x = 0;
  foreach ($Tab as $k => $v):
  ?>
    <tr class="data_row<?php echo intval(($x%2)+1); echo $v['wu_active'] > 0 ? '' : ' off'; ?>">
      <td><?php echo htmlspecialchars($v['title']); ?></td>
      <td><?php echo $T['newsletter_types'][$v['type']]; ?></td>
      <td><?php echo htmlspecialchars($v['date_send']); ?></td>
      <td>
      <?php echo $v['date_last_send'] ?>
      <a href="index_newsletter_log.php?newsletter_id=<?php echo intval($v['id']); ?>" title="<?php _t('newsletter_log'); ?>"><img src="img/icon_newsletter_log_m.gif" border="0" width="20" height="20" alt="" /><?php _t('newsletter_log'); ?></a>
      </td>
      <td><?php echo htmlspecialchars($v['day_loop']); ?></td>
      <td><?php if($v['active'] == 1) echo "Tak"; ?></td>
      <td> 
<?php 
        if(($v['type'] == 3) && ($v['all_users'] == 1) ) {
        	echo $T['newsletter_all_users'];
        }
        else {
	        echo '<ul>';
	        foreach ($menuList as $key => $menu) {
	            if(is_array($v['groups']) && in_array($menu['wug_id'],$v['groups'])) {
	                echo "<li>",$menu['wug_name'],"</li>";
	            }
	        }
	        echo '</ul>';
        }
        
   ?></td>
      <td>
        <a name="i_<?php echo intval($v['id']); ?>" href="edit_newsletter.php?newsletter_id=<?php echo intval($v['id']); ?>#content" title="<?php _t('newsletter_edit'); ?>"><img src="img/icon_newsletter_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('newsletter_edit'); ?></a>
        <a name="i_<?php echo intval($v['id']); ?>" href="index_newsletter.php?action=send_test&newsletter_id=<?php echo intval($v['id']); ?>" title="<?php _t('newsletter_send_test'); ?>"><img src="img/icon_newsletter_send_test_m.gif" border="0" width="20" height="20" alt="" /><?php _t('newsletter_send_test'); ?></a>
      </td>
    </tr>
    <?php
    ++$x;
  endforeach;
  ?>
  </table>
</div>
</form>
  <br />
  <?php 
    // wyświetla stronnicowanie
    $Newsletter->getPaging($paging,$show);
    
  else: ?>
  <p class="message">
  <?php _t('no_newsletters_msg'); ?>
  </p>
  <?php endif ?>

