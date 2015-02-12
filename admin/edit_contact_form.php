<?php
require_once('_header.php');
require_once('../lib/contact_forms.php');
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';
$Error = array();
$ID = _get_post('form_id',0);
$Tab= $_POST;

switch($_POST['cmd']) {
	case 'edit':
		$Error = contact_form_validate($Tab,$T);
		if(count($Error)==0) {
			$x = contact_form_update($Tab);
			if($x>0) {
				_redirect('index_contact_forms.php#i_'.intval($x));
			} else {
				$Message = $T['update_error_msg'];
			}
		}
		break;
	case 'delete':
		
		if(intval($ID)>0 && contact_form_delete($ID)) {
			
			_redirect('index_contact_forms.php#content');
		} else {

			$Message = $T['delete_error_msg'];
		}
		break;
	default:
		break;
}
if($ID>0) {
	$Tab = contact_form_get($ID);
}

require_once('tpl/header.html.php');
require_once('tpl/edit_contact_form.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
