<?php
require_once '_header.php';
require_once '../lib/ftp.php';
_sec_authorise(ACCESS_MIN_EDITOR);

$res = false;
$tab = array();

parse_str($_POST['data'], $arr);
$gallery_id = intval($_POST['group_id']);
$dir = $_POST['dir'];
$tab = $arr['file_list'];

$oo = -1; // old order
$no = -1; // new order
$changed_id = 0;
group_files_reorder2($gallery_id, $tab);
/*
$orders = group_files_list($gallery_id, $dir);
if (count($orders)==count($tab) && $gallery_id>0) {	
	// przelecenie od góry do dołu
	for ($o=0,$lim=count($orders);$o<$lim && ($oo<0 || $no<0);++$o) {
		$id = $orders[$o]['file_id'];
		if($no<0 && $id!=$tab[$o]) {
			$no = $o+1;
			$changed_id = $tab[$o];
		}
		if($no>0 && $oo<0 && $id==$changed_id) {
			$oo = $o+1;
		}
	}

	// przelecenie od dolu do gory
	$oo2= -1;
	$no2= -1;
	$changed_id2 = 0;
	
	for($o=count($orders)-1;$o>=0 && ($oo2<0 || $no2<0);--$o) {
		$id = $orders[$o]['file_id'];
		if($no2<0 && $id!=$tab[$o]) {
			$no2 = $o+1;
			$changed_id2 = $tab[$o];
		}
		if($no2>0 && $oo2<0 && $id==$changed_id2) {
			$oo2 = $o+1;
		}
	}

	//trzeba wybrać tą parę oo no gdzie różnica jest większa
	if (abs($no2-$oo2)>abs($no-$oo)) {
		$no = $no2;
		$oo = $oo2;
	}
	$res = group_files_reorder($oo,$no,$gallery_id);	

}
*/

echo $res ? 'ok' : 'false';

require_once '_footer.php';