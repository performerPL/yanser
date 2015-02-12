<?php /* Smarty version 2.6.18, created on 2011-12-28 16:40:41
         compiled from mod_opinions/admin/list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'mod_opinions/admin/list.html', 35, false),)), $this); ?>
<?php echo '
<script src="js/jquery/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
'; ?>

var pagingId = "<?php echo $this->_tpl_vars['out']['paging']->uniqueId; ?>
";
<?php echo '

jQuery(document).ready(function(){
    // zdarzenie na klikniecie w link
    jQuery("#"+pagingId+" a[name=page]").live("click",function() {
        var paramsUrl = jQuery.param(jQuery("[name^=params]"));
        if(paramsUrl != \'\')
            jQuery(this).attr( "href",jQuery(this).attr("href")+\'&\'+paramsUrl );
    });

    // zdarzenie na zmianę jednego z parametrow
    jQuery("#"+pagingId+" [name^=params]").live("change",function() {
        var paramsUrl = jQuery.param(jQuery("[name^=params]"));
        if(paramsUrl != \'\')
        	window.location = \'?page=1&\'+paramsUrl;
    });
});
</script>
'; ?>



<div id="<?php echo $this->_tpl_vars['out']['paging']->uniqueId; ?>
" align="center">


Wyświetlaj: 
<?php echo smarty_function_html_options(array('name' => 'params[activity]','options' => $this->_tpl_vars['out']['params']['activityList'],'selected' => $this->_tpl_vars['out']['params']['activity']), $this);?>


<br>
Wyświetlaj wyniki na stronie po: 
<?php echo smarty_function_html_options(array('name' => 'params[limit]','options' => $this->_tpl_vars['out']['params']['limitList'],'selected' => $this->_tpl_vars['out']['paging']->limit), $this);?>



<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'core/paging_get.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>