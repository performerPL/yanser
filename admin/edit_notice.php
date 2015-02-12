<?php
require_once '_header.php';
require_once '../lib/notice.php';
require_once 'class/Notice.class.php';
_sec_authorise(ACCESS_MIN_ADMIN);

$Message = '';
$Error = array();
$ID = _get_post('n_id', 0);
$Tab = $_POST;
// gdy został klikniety przycisk Usun
if(isset($_POST['submit_del']))
    $_POST['cmd'] = 'delete';
$TMP = notice_get($ID);
switch ($_POST['cmd']) {
	case 'edit':
		$Tab['n_user'] = $TMP['n_user'];
		$Error = null; //www_user_validate($Tab, $T);
		if (count($Error)==0) {
			// pobiera czas ważności ogłoszenia
			$Tab[duration] = NOTICE_DURATION;
			notice_update($Tab,true);
			$x = notice_update($Tab);
			if ($x > 0) {
				if ($_GET['f'] == 'nn') {
					_redirect('index_notice_notice.php?user_id=' . $TMP['n_user'] . '#i_' . intval($x));
				} else {
					_redirect('index_notice_groups.php?group_id=' . $_GET['group_id'] . '#i_' . intval($x));
				}
			} else {
				$Message = $T['update_error_msg'];
			}
		}
		break;

	case 'delete':
		if (intval($ID) > 0 && notice_delete($ID)) {
			if (_sec_authorised(ACCESS_MIN_ADMIN)) {
				if ($_GET['f'] == 'nn') {
					_redirect('index_notice_notice.php?user_id=' . $TMP['n_user'] . '#content');
				} else {
					_redirect('index_notice_notice.php?group_id=' . $_GET['group_id'] . '#content');
				}
			} else {
				_redirect('index.php');
			}
		} else {
			$Message = $T['delete_error_msg'];
		}
		break;

	default:
		break;
}
if ($ID > 0) {
	$Tab = _merge(notice_get($ID), $Tab);
}



require_once 'tpl/header.html.php';
require_once 'tpl/edit_notice.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';
