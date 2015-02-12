<?php
if (!defined('_APP')) {
	exit;
}
?>

<?php
if ($ID > 0) {
	?>
<div class="oper"><a href="javascript:remove()"
	title="<?php _t('promotion_delete'); ?>" class="delete"><img
	src="img/icon_promotion_delete_m.gif" width="20" height="20" alt=""
	border="0" /><?php _t('promotion_delete'); ?></a></div>
	<?php } ?>


<div class="history"><?php
if($ID>0) {
	?> <img src="img/icon_promotion_edit.gif" width="64" height="64"
	border="0" alt="" /> <?php
} else {
	?> <img src="img/icon_promotion_add.gif" width="64" height="64"
	border="0" alt="" /> <?php
}
?> <a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
<a
	href="index_promotion.php<?php echo $ID > 0 ? '#i_' . intval($ID) : '#content'; ?>"
	title="<?php _t('promotion_mgmt'); ?>"><?php _t('promotion_mgmt'); ?></a>
<?php _t('promotion_edit');
if (isset($Message) && $Message != '') {
	?>
<div class="message"><?php echo $Message; ?></div>
	<?php
}
?></div>

<?php
if ($ID > 0) {
	?>

<div class="content_block"><script type="text/javascript">
	function remove() {
		if(confirm('<?php addslashes(_t('promotion_delete_confirm')); ?>')) {
			document.deleteFrm.submit();
		}
	}
	</script> <?php
	_gui_form_start('deleteFrm','','post',false);
	_gui_hidden('cmd','delete');
	_gui_hidden('promotion_id',intval($ID));
	_gui_form_end(false);
	//_gui_stats(array(		$T['id'] => $Tab['promotion_id'],	));
}

_gui_form_start('editFrm','edit_promotion.php');
_gui_hidden('cmd','edit');
_gui_hidden('promotion_id',intval($ID));
_gui_hidden('promotion_name',intval($Tab['promotion_name']));

_gui_checkbox('active',$T['promotion_active'],1,$Tab['active']>0,$Error['promotion_active']);
_gui_text('promotion_code',$T['promotion_code'],$Tab['promotion_code'],120,true,$Error['promotion_code']);
_gui_break();
foreach($GL_CONF['LANG'] as $lang=>$v) {
	_gui_form_row();
	echo '<img src="'.htmlspecialchars($v['LANG_FLAG']).'" alt="'.$lang.'" width="16" />';
	_gui_form_row_mid();
	echo htmlspecialchars($v['LANG_NAME']);
	_gui_form_row_end();
	_gui_text('name['.$lang.']',$T['promotion_name'],$Tab['name'][$lang],255,true,$Error['promotion_name'][$lang]);
}
_gui_break();

_gui_checkbox('article_group',$T['article_group'],1,$Tab['article_group']>0,$Error['article_group'],'',array('onclick'=>'article_groupClick()'));
_gui_block_start('allow_endless_div',($Tab['article_group']>0));
_gui_checkbox('allow_endless',$T['allow_endless'],1,$Tab['allow_endless']>0,$Error['allow_endless']);
_gui_block_end();
_gui_checkbox('rss',$T['rss'],1,$Tab['rss']>0,$Error['rss']);
_gui_checkbox('search',$T['promotion_search'],1,$Tab['search']>0,$Error['search']);
_gui_break();

foreach ((array)$Tab['menu_list'] as $key => $menu) {
	$access = 0;
	foreach ((array)$Tab['menu_access'] as $key1 => $menu_access) {
		if ($access == 1) {
			continue;
		}
		if ($menu_access['menu_id'] == $menu['menu_id']) {
			$access=1;
		}
	}
	_gui_checkbox('allow_menu_access['.$menu['menu_id'].']',$T['allow_menu_access'].$menu['menu_name'],1,$access,$Error['allow_upload']);
}
/*
_gui_break($T['newsletter_groups']);
foreach ($Tab['newsletter_group_list'] as $key => $group) {
	$access=0;
	if(is_array($Tab['newsletter_group_access']) && array_key_exists($group['wug_id'],$Tab['newsletter_group_access'])) {
		$access = 1;
	}

	_gui_checkbox('newsletter_group_access['.$group['wug_id'].']', '' . $group['wug_name'],1,$access);
}
*/

_gui_break();
echo '<div class="space"></div><div id="global_btn">';
_gui_button($T['cancel'],'location.href=\'index_promotion.php'.($ID>0?'#i_'.intval($ID):'#content').'\'');
_gui_button($T['ok'],'','editFrm');
echo '</div>';

_gui_form_end();


?></div>
<br />

<script type="text/javascript">
	function article_groupClick() {
		var f = document.getElementById('article_group');
		var div = document.getElementById('allow_endless_div');
		if(f.checked) {
			div.style.display='none';
		} else {
			div.style.display='block';
		}
	}
	</script>
