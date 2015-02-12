<?php 
if (!defined('_APP')) {
  exit;
}

echo '<div class="global_top2">';
$mod_name = '';
foreach ($GL_MOD_TYPE as $k => $v) {
  if ($k == $_GET['module_type']) {
    $mod_name = $v->name;
  }
}
	echo 'Modul: ' . $T[$mod_name];
echo '</div><br />';

	_gui_hidden('cmd','edit');
	_gui_hidden('module_id',intval($ID));
	_gui_hidden('module_type',intval($Type));
	_gui_hidden('article_id',intval($ArticleID));

	_gui_text('module_name',$T['item_mod_name'],$Tab['module_name'],255,true,$Error['module_name']);
	_gui_checkbox('module_active',$T['item_mod_active'],1, $Tab['module_active'] > 0, $Error['active']);
	_gui_checkbox('show_module_title',$T['item_mod_show_title'],1,$Tab['show_module_title']>0);
//var_dump($GL_MOD_TYPE);
	if (count($GL_MOD_TYPE[$Tab['module_type']]->style) > 0) {
		_gui_select('module_style',$T['item_mod_style'],$Tab['module_style'],$GL_MOD_TYPE[$Tab['module_type']]->style,'','mod_style_txt_func');
	} else {
		_gui_hidden('module_style',$Tab['module_style']);
	}
	function mod_style_txt_func($k, $v) 
	{
		global $T;
		return $T[$v];
	}
	_gui_select('access_level',$T['item_mod_access_level'],$Tab['access_level'],$AccessLevel); //access_level
	// kod vip
	_gui_text('vip_code',$T['item_mod_vip_code'],$Tab['vip_code'],255,false,$Error['vip_code']);

	if ($include_form != '') {
		require_once $include_form;
	}
	
// zatwierdzenie modulu
echo '<br /><div class="space"></div>';	
echo '<div id="global_btn1">';
		_gui_button($T['cancel'],'popup_cancel()');
		_gui_button($T['ok'],'popup_submit()');
echo '</div>';

