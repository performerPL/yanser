<?php if (!defined('_APP')) exit; ?>

<?php if ($ID>0): ?>

	<div class="oper">
	
	<a	onclick="return confirm('<?php _t('newsletter_send_confirm'); ?>');"
	href="index_newsletter.php?action=send&newsletter_id=<?php echo intval($ID); ?>"
	title="<?php _t('newsletter_send'); ?>">
	<img src="img/icon_newsletter_send_m.gif" border="0" width="20" height="20"
	alt="" /><?php _t('newsletter_send'); ?></a>
	
	&nbsp;&nbsp;&nbsp;
	
	<a 	href="index_newsletter.php?action=send_test&newsletter_id=<?php echo intval($ID); ?>"
	title="<?php _t('newsletter_send_test'); ?>">
	<img src="img/icon_newsletter_send_test_m.gif" border="0" width="20"
	height="20" alt="" /><?php _t('newsletter_send_test'); ?></a> 

	&nbsp;&nbsp;&nbsp;
	
	<?php if (_sec_authorised(ACCESS_MIN_ADMIN)): ?> 
		<a href="javascript:remove()" title="<?php _t('newsletter_delete'); ?>"
		class="delete"><img src="img/icon_newsletter_delete_m.gif" width="20"
		height="20" alt="" border="0" /><?php _t('newsletter_delete'); ?></a> 
	<?php endif ?>
	
	</div>

<?php endif ?>

<div class="history"><?php if ($ID>0): ?> <img
	src="img/icon_newsletter_edit.gif" width="64" height="64" border="0"
	alt="" /> <?php else: ?> <img src="img/icon_newsletter_add.gif"
	width="64" height="64" border="0" alt="" /> <?php endif ?> <a
	href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
<a
	href="index_newsletter.php<?php echo $ID>0?'#i_'.intval($ID):'#content'; ?>"
	title="<?php _t('newsletter_mgmt'); ?>"><?php _t('newsletter_mgmt'); ?></a>
<?php _t('newsletter_edit');
if (isset($Message) && $Message!=''):
?>
<div class="message"><?php echo $Message; ?></div>
<?php endif ?></div>

<?php if ($ID > 0): ?>

<script type="text/javascript">
  function remove() {
    if(confirm('<?php addslashes(_t('newsletter_delete_confirm')); ?>')) {
      document.deleteFrm.submit();
    }
  }
  </script>


<div class="content_block"><?php
_gui_form_start('deleteFrm','','post',false);
_gui_hidden('cmd','delete');
_gui_hidden('newsletter_id',intval($ID));
_gui_form_end(false);
/*_gui_stats(array(
 $T['id'] => $Tab['newsletter_id'],
 $T['created'] => $Tab['created'],
 $T['last_login'] => $Tab['last_login'],
 )); */
endif;

_gui_form_start('editFrm','edit_newsletter.php');
_gui_hidden('cmd','edit');
_gui_hidden('id', intval($ID));

_gui_break();
_gui_text('title', $T['newsletter_title'], $Tab['title'], 255, true, $Error['title']);
_gui_select('type',$T['newsletter_type'],$Tab['type'],$T['newsletter_types'],'','',false,'');
_gui_date('date_send',$T['newsletter_date_send'],$Tab['date_send']);
_gui_textarea('email_content',$T['newsletter_email_content'],$Tab['email_content'],50,10,WYSIWYG_FULL, true,'');
_gui_checkbox('active',$T['newsletter_active'],1,$Tab['active']>0,'');
_gui_text('day_loop', $T['newsletter_day_loop'], $Tab['day_loop'], 100, true, $Error['day_loop']);

_gui_break();

// dla typu Specjalny, dajemy opcję do wszystkich
if($Tab['type'] == 3) {
	echo '<div id="all_users_div" style="display:block;">';
}
// dla pozostałych opcja jest niewidoczna
else {
	echo '<div id="all_users_div" style="display:none;">';
}
_gui_checkbox('all_users',$T['newsletter_all_users'],1,$Tab['all_users']>0);
echo '</div>';

_gui_break();
foreach ($Tab['menu_list'] as $key => $menu) {
	$access=0;
	if(is_array($Tab['groups']) && in_array($menu['wug_id'],$Tab['groups'])) {
		$access = 1;
	}

	_gui_checkbox('groups['.$menu['wug_id'].']', '' . $menu['wug_name'],$menu['wug_id'],$access);
}

_gui_break();
echo '<div class="space"></div><div id="global_btn">';
_gui_button($T['cancel'],'location.href=\'index_newsletter.php'.($ID > 0 ? '#i_'.intval($ID) : '#content').'\'');
_gui_button($T['ok'],'','editFrm');
echo '</div>';
_gui_form_end();
_gui_break();


?></div>
<br />

<script type="text/javascript">

jQuery(document).ready(function(){
    // inicjuje drzewko
    jQuery("#type").live((jQuery.browser.msie ? "click" : "change"),function() {
        if(jQuery(this).val() == 3) {
            jQuery("#all_users_div").attr("style","display:block;");
        }
        else {
        	jQuery("#all_users_div").attr("style","display:none;");
        	jQuery("#all_users").attr("checked","false");
        }
    });
});
</script>