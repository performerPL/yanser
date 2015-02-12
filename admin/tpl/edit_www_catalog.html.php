<?php if(!defined('_APP')) exit; ?>

	<div class="oper">
		<?php if($ID > 0) {?>
		<a href="javascript:remove()" title="<?php _t('www_catalog_delete'); ?>" class="delete"><img src="img/icon_menu_delete_m.gif" width="20" height="20" alt="" border="0" /><?php _t('www_catalog_delete'); ?></a>		
	    <?php } ?>
	</div>
	
	
<div class="history">
	<?php
	if($ID>0) {
		?>
		<img src="img/icon_menu_edit.gif" width="64" height="64" border="0" alt="" /> 
		<?php
	} else {
		?>
		<img src="img/icon_menu_add.gif" width="64" height="64" border="0" alt="" /> 
		<?php
	}
	?>
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<a href="index_www_catalog.php<?php echo $ID>0?'#i_'.intval($ID):'#content'; ?>" title="<?php _t('www_catalog_mgmt'); ?>"><?php _t('www_catalog_mgmt'); ?></a>
	<?php _t('menu_edit'); 	
	if(isset($Message) && $Message!='') {
	?>
	
	<div class="message">
		<?php echo $Message; ?>
	</div>
	<?php
}
	?>
</div>

<?php
if($ID>0) {
	?>

	
	

	<script type="text/javascript">
	function remove() {
		if(confirm('<?php addslashes(_t('www_catalog_delete_confirm')); ?>')) {
			document.deleteFrm.submit();
		}
	}
	</script>
	<?php
	_gui_form_start('deleteFrm','','post',false);
	_gui_hidden('cmd','delete');
	_gui_hidden('id',intval($ID));
	_gui_form_end(false);
}	

?>
<div class="content_block">
<?

_gui_form_start('editFrm','edit_www_catalog.php');
	_gui_hidden('cmd','edit');
	_gui_hidden('id',intval($ID));
	
	_gui_text('title',$T['www_catalog_title'],$Tab['title'],120,true,$Error['title']);
	_gui_textarea('description',$T['www_catalog_description'],$Tab['description'],50,10,WYSIWYG_NONE, true,'',$T['www_catalog_description']);
	_gui_text('url',$T['www_catalog_url'],$Tab['url'],120,true,$Error['url']);
	_gui_checkbox('active',$T['www_catalog_active'],1,$Tab['active']>0,'');
	
	?>
	<link rel="stylesheet" href="../js/jquery/jquery-treeview/jquery.treeview.css" />
    <link rel="stylesheet" href="../js/jquery/jquery-treeview/screen.css" />
    <script src="../js/jquery/jquery.cookie.js" type="text/javascript"></script>
    <script type="text/javascript" src="../js/tiny_mce/tiny_mce.js"></script>
    <script src="../js/jquery/jquery-treeview/jquery.treeview.js" type="text/javascript"></script>
	<script type="text/javascript">
	tinyMCE.init({
	    mode : "textareas",
	    theme : "simple",
	    width : '100%',
	    height : '200'
	});
	
	jQuery(document).ready(function(){
	    // inicjuje drzewko
	    jQuery("#groups_tree").treeview({
	         collapsed: true,
	         animated: "fast",
	         unique: true        
	    });
	    
	});
	</script>
	<div class="row">
	<div class="row_left"></div>
	<div class="row_right">
	<ul id="groups_tree" class="filetree">
    <?php echo $generatedGroupList ?>
    </ul>
    </div>
    </div>
	<?php 
	_gui_break();
echo '<div class="space"></div><div id="global_btn">';
		_gui_button($T['cancel'],'location.href=\'index_www_catalog.php'.($ID>0?'#i_'.intval($ID):'#content').'\'');
		_gui_button($T['ok'],'','editFrm');
echo '</div>';
_gui_form_end();


?>

</div><br />