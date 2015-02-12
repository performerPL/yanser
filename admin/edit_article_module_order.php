<?php
require_once('_header.php');
require_once('../lib/user.php');
require_once('../lib/article.php');
require_once('../lib/item.php');
_sec_authorise(ACCESS_MIN_EDITOR);

/*
$res= false;
$tab = array();

parse_str($_POST['data'],$arr);
$parent_id = intval($_POST['parent_id']);

$tab = $arr['mod_list'];

$oo = -1; // old order
$no = -1; // new order
$changed_id = 0; // id przesuniętego itemui



$orders = article_mod_get_orders($parent_id);
// var_dump($orders);




if(count($orders)==count($tab) && $parent_id>0) {	
	// przelecenie od góry do dołu
	for($o=0,$lim=count($orders);$o<$lim && ($oo<0 || $no<0);++$o) {
		$id = $orders[$o]['module_id'];
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
		$id = $orders[$o]['module_id'];
		if($no2<0 && $id!=$tab[$o]) {
			$no2 = $o+1;
			$changed_id2 = $tab[$o];
		}
		if($no2>0 && $oo2<0 && $id==$changed_id2) {
			$oo2 = $o+1;
		}
	}

	//trzeba wybrać tą parę oo no gdzie różnica jest większa
	if(abs($no2-$oo2)>abs($no-$oo)) {
		$no = $no2;
		$oo = $oo2;
	}
	
	
	$res =article_mod_reorder($oo,$no,$parent_id);	

}

// var_dump($no);


echo $res?'ok':'false';
*/


$pattern = '/\d{1,10}/';
preg_match_all($pattern, $_POST['data'], $matches);
if(is_array($matches[0]))
{
	foreach($matches[0] as $key=>$row)
	{
		set_article_mod_update_order($key+1, $row, $_POST['parent_id']);
	}
}



require_once('_footer.php');