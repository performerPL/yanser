<?php
function smarty_function_viva($params, &$smarty) {

    if (!isset($params['name'])) {
        $smarty->trigger_error("viva: missing 'name' parameter", E_USER_WARNING);
        return;
    }

    if (!isset($params['table'])) {
        $smarty->trigger_error("viva: missing 'var' parameter", E_USER_WARNING);
        return;
    }
    $NAME = $params['name'];
    $TABLE = $params['table'];
	$return = '';
	$orderby = trim($_GET['ORDERBY']);
	$page = (int) $_GET['PAGE'];
	if ($_GET['ORDERTYPE']=='ASC') $ordertype = 'ASC'; else $ordertype = 'DESC';
	
		$return .= '<b><a href="?ID='.$_GET['ID'].'&PAGE='.$page.'&ORDERBY='.$TABLE.'&ORDERTYPE='.$ordertype.'">'.$NAME.'</a></b>';
	
	if ($orderby==$TABLE && $ordertype=='ASC') {
		$return .= ' <a href="?ID='.$_GET['ID'].'&PAGE='.$page.'&ORDERBY='.$TABLE.'&ORDERTYPE=DESC"><img src="/kernel/images/admin/up.gif" style="border:0"/></a>';
	} elseif ($orderby==$TABLE && $ordertype=='DESC') {
		$return .= ' <a href="?ID='.$_GET['ID'].'&PAGE='.$page.'&ORDERBY='.$TABLE.'&ORDERTYPE=ASC"><img src="/kernel/images/admin/down.gif" style="border:0"/></a>';
	} else {
		$return .= ' <a href="?ID='.$_GET['ID'].'&PAGE='.$page.'&ORDERBY='.$TABLE.'&ORDERTYPE=DESC"><img src="/kernel/images/admin/up.gif" style="border:0"/></a> <a href="?ID='.$_GET['ID'].'&PAGE='.$page.'&ORDERBY='.$TABLE.'&ORDERTYPE=ASC"><img src="/kernel/images/admin/down.gif" style="border:0"/></a>';
	}
	return $return;
}
