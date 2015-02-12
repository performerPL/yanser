<?php
if($_REQUEST[ajax] == 1) {
	ob_start();
}

require_once '_header.php';
require_once '../lib/notice.php';
require_once 'module/mod_notice_groups.class.php';

_sec_authorise(ACCESS_MIN_ADMIN);


if($_REQUEST[ajax] == 1) {
	// konczy buforowanie i czyści bufor
	ob_end_clean();
	// pobiera listę funkcji do wykonania
	$funcList = explode(",",$_REQUEST[func]);

	// tablica z danym json
	$json = array();
	foreach($funcList as $func) {
		switch($func) {
			// dodaje nową grupę
			case "saveGroup" :
				$id = notice_group_new((int) $_POST[parent_id]);
				$json["id"] = $id;
				break;
            // usuwa grupę
			case "removeGroup" :
				notice_group_delete((int) $_POST[id]);
				$json["removed"] = "1";
				break;
            // zapisuje nazwe grupy
			case "saveGroupName" :
				notice_group_update_name($_POST['name'], $_POST['id']);
				$json["saved"] = "1";
				break;

		    // zapisuje aktywność grupy
			case "saveGroupActive" :
				$active = $_POST[active] == "true" ? 1 : 0;
				if(notice_update_active($_POST[id],$active)) {
					$json["saved"] = "1";
				}
				break;
				
			// zapisuje koleność w grupie dla parent_id
			case "saveGroupOrder" :
                
				if(is_numeric($_REQUEST[parentId]) && is_array($_POST['item_'.$_REQUEST[parentId]])) {
					foreach($_POST['item_'.$_REQUEST[parentId]] as $index => $itemId) {
				        //zapisuje nowe ustawienie dla każdego elementu z listy
						$fields = array();
						$fields['ng_order'] = $index + 1;
						$fields['ng_parent_id'] = $_REQUEST[parentId];
						_db_update("notice_group", $fields, 'ng_id = '.$itemId);		
					}
				    $json["saved"] = 1;
				}
				
				break;
		}
	}
	
	// zwraca dane json
	echo json_encode($json);
	exit;
}

// pobiera listę grup
$groupsList = notice_group_list_all();
$noticeGroupObj = new mod_notice_groups();
$generatedGroupList = $noticeGroupObj->fetchGroups($groupsList);

require_once 'tpl/header.html.php';
require_once 'tpl/index_notice_groups.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
