<?php if(!defined('_APP')) exit;?>

<div class="oper">
<a href="edit_www_catalog.php?id=0#content" title="<?php _t('www_catalog_add'); ?>"><img src="img/icon_menu_add_m.gif" border="0" width="20" height="20" alt="" /><?php _t('www_catalog_add'); ?></a>
</div>

<div class="history">
	<img src="img/icon_menu.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<?php _t('www_catalog_mgmt'); ?>
</div>

<?php
if(isset($Message) && $Message!='') {
	?>
	<div class="message">
		<?php echo $Message; ?>
	</div>
	<?php
}
?>
<!--  index menu -->


<?php
_gui_stats($Stats);
?>
	<div class="content_block">
       <link rel="stylesheet" href="../js/jquery/jquery-treeview/jquery.treeview.css" />
    <link rel="stylesheet" href="../js/jquery/jquery-treeview/screen.css" />
    <script src="../js/jquery/jquery.cookie.js" type="text/javascript"></script>
    <script src="../js/jquery/jquery-treeview/jquery.treeview.js" type="text/javascript"></script>
    <script type="text/javascript">
    
    jQuery(document).ready(function(){
        // inicjuje drzewko
        jQuery("#groups_tree").treeview({
             collapsed: true,
             animated: "fast",
             unique: true        
        });

        // dodaje obsluge klikniecia na checkbox z aktywnościa grupy 
        jQuery("#groups_tree input[name=allow_menu_access[]]").live("click",function() {
            // pobiera nadrzędny dokument
            var parentElem = jQuery(this).parent().parent();
            var elem = jQuery(this);
  
            // ustawia znacznik aktywności wszystkich podgrup
            if(jQuery(elem).attr("checked"))
                parentElem.find("input[name=allow_menu_access[]]").attr("checked","checked");
            else
                parentElem.find("input[name=allow_menu_access[]]").attr("checked","");
               

        });
        
    });
    </script>
<?php 
    _gui_form_start('filterForm','index_www_catalog.php');
    _gui_hidden('cmd','filter');
?>
    
    <ul id="groups_tree" class="filetree">
    <?php echo $generatedGroupList ?>
    </ul>
<?php 
       _gui_break();
       echo '<div class="space"></div><div id="global_btn">';
       _gui_button($T['filter'],'','filterForm');
       echo '</div>';
       _gui_form_end();
?>
    </div>
	
	<div class="content_block">
	<table class="data" cellspacing="1" width="100%">
	<tr>
		<th>Id</th>
		<th><?php _t('www_catalog_url'); ?></th>
		<th><?php _t('www_catalog_active'); ?></th>
		<th>&nbsp;</th>
	</tr>
	<?php
	$x=  0;
	foreach($Tab as $k=>$v) {
		?>
		<tr class="data_row<?php echo intval(($x%2)+1);?>">
			<td><?php echo htmlspecialchars($v['id']); ?></td>
			<td><?php echo htmlspecialchars($v['url']); ?></td>
			<td><?php if($v['active'] == 1) echo _t('yes'); ?></td>
			<td><a name="i_<?php echo intval($v['id']); ?>" href="edit_www_catalog.php?id=<?php echo intval($v['id']); ?>#content" title="<?php _t('www_catalog_edit'); ?>"><img src="img/icon_menu_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('www_catalog_edit'); ?></a></td>
		</tr>
		<?php
		++$x;
	}
	?>
	</table>
	
	</div>
	
<?php 
    // wyświetla stronnicowanie
    $wwwCatalog->getPaging($paging,$criteria);
?>

<br />