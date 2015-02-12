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

<div class="group_path">
<?php
$first = true;
if (intval($_GET['PARENT']) > 0) {
  $first = false;
  echo '<a href="?PARENT=0">Główna grupa</a>';
}
foreach ($PATHWAY as $k => $V) {
  if ($first == true) {
    $first = false;
  } else {
    echo ' / ';
  }
  echo '<a href="?PARENT=' . $V['ng_id'] . '">' . $V['ng_name'] . '</a>';
}
$now = (int) $_GET['PARENT'];
$RES = _db_get_one("SELECT ng_name FROM " . DB_PREFIX . "notice_group WHERE ng_id=" . _db_int($now) . " LIMIT 1");
if (!$first) {
  echo ' / ';
}
echo $RES['ng_name'];
?>
</div>
<form method="post" name="forma">
<input type="hidden" name="cmd" id="i_cmd" value="updateactive"/>
<input type="hidden" name="i_value" id="i_value" value=""/>
<input type="hidden" name="i_value2" id="i_value2" value=""/>
<div class="content_block">
<?php
//_gui_stats($Stats);

if (count($Tab) > 0) {
  ?>
  <table class="data" width="100%">
  <tr>
    <th>Grupa</th>
    <th>Aktywacja</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
  <?php
  $x = 0;
  function _digg_groups($Tab, $delim = '', $parent = 0)
  {
   foreach ($Tab as $k => $V) {
     echo '<tr class="data_row">';
     echo '<td>' . $delim . ' ' . $V['ng_name'] . '</td>';
     echo '<td>';
     $checked = '';
     if ($V['ng_active'] > 0) {
       $checked = 'checked="checked"';
     }

     echo '<input type="checkbox" value="1" name="actives[' . $V['ng_id'] . ']" ' . $checked . '/>'; 
     echo ' </td>';
     echo '<td><a href="?PARENT=' . $V['ng_id'] . '">PODGRUPY</a></td>';
     echo '<td><a name="i_' . intval($V['ng_id']) . '" href="index_notice_notice.php?group_id=' . intval($V['ng_id']) . '" title="Edytuj grupę"><img src="img/icon_user_edit_m.gif" border="0" width="20" height="20" alt="" />Zobacz ogłoszenia</a>';
     echo '</td>';
     echo '</tr>';
   }
  }
  _digg_groups($Tab);
 ?>
  </table>
  <!-- nawigacja po stronacch ewentualnie<div class="navbar">
  </div>-->
  <div class="space"></div><div id="global_btn">
  <input class="btn" type="submit" value=" OK "/>
</div>
  </div><br />
  </form>
  <?php
} else {
  ?>
  <p class="message">
  <?php _t('no_users_msg'); ?>
  </p>
  <?php
}
?>
