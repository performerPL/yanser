<?php
require_once('_header.php');
require_once('../lib/user.php');
require_once('../lib/article.php');
require_once('../lib/item.php');
_sec_authorise(ACCESS_MIN_EDITOR);

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';


$res= false;

parse_str($_POST['data'],$arr);

$parent = $_POST['parent_id'];

$parent_id=0;
$tab = array();

if($parent=='item_tree') {
	$parent_id= 0;
	$tab = $arr['item_tree'];
} else {
	$parent_id = explode('_',$parent);
	$parent_id = intval($parent_id[1]);
	$tab = $arr['it_'.$parent_id];
}

$oo = -1; // old order
$no = -1; // new order
$changed_id = 0; // id przesuniętego itemui

$showArchive = $_POST[show_archive] == 'true' ? true : false;

$orders = item_get_orders($parent_id, $_POST["menu_id"],"i.item_order",$showArchive);
// var_dump($orders);

if(count($orders)==count($tab)) {
	
	
	for($o=0,$lim=count($orders); $o<$lim && ($oo<0 || $no<0); ++$o) {
		$id = $orders[$o]['item_id'];
		if($no<0 && $id!=$tab[$o]) {
//			$no = $o+1;
			$no = $orders[$o]['item_order'];
			$changed_id = $tab[$o];
		}
		if($no>0 && $oo<0 && $id==$changed_id) {
//			$oo = $o+1;
			$oo = $orders[$o]['item_order'];
		}
	}
	
	// przelecenie od dolu do gory
	$oo2= -1;
	$no2= -1;
	$changed_id2 = 0;
	
	for($o=count($orders)-1;$o>=0 && ($oo2<0 || $no2<0);--$o) {
		$id = $orders[$o]['item_id'];
		if($no2<0 && $id!=$tab[$o]) {
//			$no2 = $o+1;
			$no2 = $orders[$o]['item_order'];
			$changed_id2 = $tab[$o];
		}
		if($no2>0 && $oo2<0 && $id==$changed_id2) {
//			$oo2 = $o+1;
			$oo2 = $orders[$o]['item_order'];
		}
	}

	//trzeba wybrać tą parę oo no gdzie różnica jest większa
	if(abs($no2-$oo2)>abs($no-$oo)) {
		$no = $no2;
		$oo = $oo2;
	}
	
//	echo 'o:'.$oo;
//	echo 'n:'.$no;
//	echo 'p:'.$parent_id;
	$res =item_reorder($oo,$no,$parent_id, $_POST["menu_id"]);
}
// gdy liczba nie jest równa
else {
//	echo count($tab).'  ';
//	echo count($orders);
}


_db_order_recompute('item', 'item_order', 'item_id', array('menu_id' => $_POST["menu_id"], 'parent_id' => $parent_id));

echo $res?'ok':'false';

require_once('_footer.php');