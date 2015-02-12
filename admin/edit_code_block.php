<?php
require_once('_header.php');
require_once('../lib/code_blocks.php');
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';
$Error = array();
$ID = _get_post('code_block_id',0);
$Tab= $_POST;

switch($_POST['cmd']) {
	case 'edit':
		$Error = code_block_validate($Tab,$T);
		if(count($Error)==0) {
			$x = code_block_update($Tab);
			if($x>0) {
				_redirect('index_code_blocks.php#i_'.intval($x));
			} else {
				$Message = $T['update_error_msg'];
			}
		}
		break;
	case 'delete':
		
		if(intval($ID)>0 && code_block_delete($ID)) {
			
			_redirect('index_code_blocks.php#content');
		} else {

			$Message = $T['delete_error_msg'];
		}
		break;
	default:
		break;
}
if($ID>0) {
	$Tab = code_block_get($ID);
}

require_once('tpl/header.html.php');
require_once('tpl/edit_code_block.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
