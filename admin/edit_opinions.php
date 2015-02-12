<?php
require_once('_header.php');
require_once('../lib/user.php');
require_once('../lib/menu.php');
require_once('../lib/item.php');
require_once('../module/mod_opinions.class.php');
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';
$Error = array();
$ID = _get_post('module_id',0);
if(!$ID)
	_redirect('index_opinions.php');

$module = new mod_opinions();
$opinion = $module->get($ID);
if(!$opinion)
	_redirect('index_opinions.php');

switch($_REQUEST['cmd']) {
	case 'accept':
		$module->accept($_REQUEST['module_id'], $_REQUEST['opinion_id']);
		break;
	case 'delete':
		$module->deleteOpinion($_REQUEST['module_id'], $_REQUEST['opinion_id']);
		break;
	case 'deny':
		$module->deny($_REQUEST['module_id'], $_REQUEST['opinion_id']);
		break;
	default:
		break;
}
$Tab = $module->getAllOpinions($ID);


require_once('tpl/header.html.php');
require_once('tpl/edit_opinions.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
