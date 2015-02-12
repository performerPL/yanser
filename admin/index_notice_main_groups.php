<?php
require_once '_header.php';
require_once '../lib/notice.php';
require_once 'module/mod_notice_groups.class.php';

$mainGroupsList = notice_main_group_list();
$noticeGroupObj = new mod_notice_groups();
$generatedMainGroupList = $noticeGroupObj->fetchMainGroups($mainGroupsList);


if($_REQUEST[ajax] == 1) {
	// konczy buforowanie i czyści bufor
	ob_end_clean();
	switch($_REQUEST[func]) {
		case "saveGroup" :
			$id = notice_main_group_new();
			echo json_encode(array("id"=>$id));
			exit;		
			break;
		
		case "removeGroup" :
			notice_main_group_delete((int) $_POST[id]);
			echo json_encode(array("removed"=>"1"));
			exit;
			break;
			
		case "saveGroupName" :
			notice_main_group_update_name($_POST['name'], $_POST['id']);
			echo json_encode(array("saved"=>"1"));
			exit;
			break;
			
		case "saveGroupActive" :
			$active = $_POST[active] == "true" ? 1 : 0;
   			if(notice_main_update_active($_POST[id],$active)) {
				echo json_encode(array("saved"=>"1"));
   			}
   			exit;
			break;
	}
}

// załącza szablon
require_once 'tpl/index_notice_main_groups.html.php';
