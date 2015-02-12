<?php
require_once '_header.php';
require_once '../lib/config.php';
require_once '../lib/config_value.php';
_sec_authorise(ACCESS_MIN_SUPERADMIN);

$Message = '';
$Error = array();
$ConfID = _get_post('config_id', 0);
$ID = _get_post('value_id', 0);

$Tab= $_POST;
$Config = array();

if (intval($ConfID) <= 0) {
  _redirect('index_config_value.php#content');
} else {
  $Conf = config_get_full($ConfID);
}

switch ($_POST['cmd']) {
  case 'edit':
    $Error = config_value_validate($Tab, $Conf, $T);
    //var_dump(count($Error)==0);
    if (count($Error)==0) {
      $x = config_value_update($Tab, $Conf);
      if ($x>0) {
        _redirect('index_config_value.php#i_'.intval($ConfID));
      } else {
        $Message = $T['update_error_msg'];
      }
    }
    break;
  case 'delete':

    if (intval($ID)>0 && config_value_delete($ID, $Conf)) {
      _redirect('index_config_value.php#i_'.intval($ConfID));
    } else {
      $Message = $T['delete_error_msg'];
    }
    break;
  default:
    break;
}

// jesli to jest wymagana wartosc dla konfiga, toi trzeba ja utworzyc
if (intval($Conf['multiple'])<=0) {
  $ID = config_value_create($Conf);
}

if ($ID > 0) {
  $Tab = _merge(config_value_get($ID,$Conf),$Tab);
}

require_once 'tpl/header.html.php';
require_once 'tpl/edit_config_value.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
