<?php
if($_REQUEST[ajax] == 1) {
    ob_start();
}

require_once '_header.php';
require_once '../lib/www_user.php';
require_once 'class/UserWWW.class.php';

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
                $id = www_user_group_new((int) $_POST[parent_id]);
                $json["id"] = $id;
                break;
            // usuwa grupę
            case "removeGroup" :
                www_user_group_delete((int) $_POST[id]);
                $json["removed"] = "1";
                break;
            // zapisuje nazwe grupy
            case "saveGroupName" :
                www_user_group_update_name($_POST['name'], $_POST['id']);
                $json["saved"] = "1";
                break;

            // zapisuje aktywność grupy
            case "saveGroupActive" :
                $active = $_POST[active] == "true" ? 1 : 0;
                if(www_user_update_active($_POST[id],$active)) {
                    $json["saved"] = "1";
                }
                break;
                
            // zapisuje koleność w grupie dla parent_id
            case "saveGroupOrder" :
                
                if(is_numeric($_REQUEST[parentId]) && is_array($_POST['item_'.$_REQUEST[parentId]])) {
                    foreach($_POST['item_'.$_REQUEST[parentId]] as $index => $itemId) {
                        //zapisuje nowe ustawienie dla każdego elementu z listy
                        $fields = array();
                        $fields['wug_order'] = $index + 1;
                        $fields['wug_parent_id'] = $_REQUEST[parentId];
                        _db_update("www_user_group", $fields, 'wug_id = '.$itemId);        
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


$groupsList = www_user_group_list_all();
$userWWWObj = new UserWWW();
$generatedGroupList = $userWWWObj->fetchGroups($groupsList);

$PATHWAY = www_user_get_pathway((int) $_GET['PARENT']);

require_once 'tpl/header.html.php';
require_once 'tpl/index_www_groups.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
