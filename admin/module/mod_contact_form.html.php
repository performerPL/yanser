<?php
if($_GET['form_type_id']!=''){
	$Tab['form_type']=$_GET['form_type_id'];
}
_gui_select('form_type',$T['form_type_title'],$Tab['form_type'],$Tab['form_types'],'','',false,'',$T['form_type_info']);
_gui_text('form_adres',$T['adres_title'],$Tab['form_adres'], false, false, false, $T['adres_info']);
_gui_text('form_subject',$T['form_subject_title'],$Tab['form_subject'], false, false, false, $T['form_subject_info']);
?>

