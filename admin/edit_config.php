<?php
require_once('_header.php');
require_once('../lib/config.php');
_sec_authorise(ACCESS_MIN_SUPERADMIN);


$Message = '';
$Error = array();
$ID = _get_post('config_id',0);
$Tab= $_POST;

switch($_POST['cmd']) {
	case 'edit':
		$Error = config_validate($Tab,$T);
		if(count($Error)==0) {
			$x = config_update($Tab);
			if($x>0) {
				_redirect('index_config.php#i_'.intval($x));
			} else {
				$Message = $T['update_error_msg'];
			}
		}
		break;
	case 'delete':
		
		if(intval($ID)>0 && config_delete($ID)) {
			
			_redirect('index_config_group.php#content');
		} else {

			$Message = $T['delete_error_msg'];
		}
		break;
	default:
		break;
}
if($ID>0) {
	$Tab = _merge(config_get($ID),$Tab);
}

$ConfigParents = config_list_parents($ID);
$ConfigParents = array(0=>$T['config_no_parent'])+$ConfigParents;

//var_dump($ConfigParents);

require_once('tpl/header.html.php');
require_once('tpl/edit_config.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
