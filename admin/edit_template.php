<?php
require_once '_header.php';
require_once '../lib/template.php';
_sec_authorise(ACCESS_MIN_EDITOR);


$Message = '';
$Error = array();
$ID = _get_post('template_id', 0);
$Tab = $_POST;

switch ($_POST['cmd']) {
	case 'edit':
		$Error = template_validate($Tab, $T);
		if (count($Error)==0) {
			$x = template_update($Tab);
			if ($x > 0) {
				_redirect('index_template.php#i_' . intval($x));
			} else {
				$Message = $T['update_error_msg'];
			}
		}
		break;
		
	case 'delete':
		
		if (intval($ID)>0 && template_delete($ID)) {
			_redirect('index_template.php#content');
		} else {
			$Message = $T['delete_error_msg'];
		}
		break;
		
	default:
		break;
}
if ($ID>0) {
	$Tab = _merge(template_get($ID), $Tab);
}

$Templates = template_list_dirs();

require_once 'tpl/header.html.php';
require_once 'tpl/edit_template.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
