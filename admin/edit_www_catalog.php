<?php
require_once '_header.php';
require_once 'class/WwwCatalog.class.php';
require_once '../lib/www_catalog.php';
_sec_authorise(ACCESS_MIN_EDITOR);

$Message = '';
$Error = array();
$ID = _get_post('id',0);
$Tab= $_POST;

switch($_POST['cmd']) {
	case 'edit':
		$Error = www_catalog_validate($Tab,$T);
		if(count($Error)==0) {
			$x = www_catalog_update($Tab);
			// zapisuje grupy
			if(empty($Tab['id'])) {
			    $Tab['id'] = $x;
			}
			
			www_catalog_group_in_update($Tab);
			if($x>0) {
				_redirect('index_www_catalog.php#i_'.intval($x));
			} 
			else {
				$Message = $T['update_error_msg'];
			}
		}
		break;
	case 'delete':
		
		if(intval($ID)>0 && www_catalog_delete($ID)) {			
			_redirect('index_www_catalog.php#content');
		} 
		else {
			$Message = $T['delete_error_msg'];
		}
		break;
	default:
		break;
}
if($ID>0) {
	$Tab = www_catalog_get($ID);
}

// pobiera listę grup
$wwwCatalog = new WwwCatalog();
$groupsList = $wwwCatalog->getGroupList();
$generatedGroupList = $wwwCatalog->fetchGroups($groupsList,$wwwCatalog->filterGroups(www_catalog_get_group_access($ID)));

require_once('tpl/header.html.php');
require_once('tpl/edit_www_catalog.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
