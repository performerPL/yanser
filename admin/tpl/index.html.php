<?php 
if (!defined('_APP')) {
  exit; 
}
?>

<br />
<div class="space"></div>
<div class="head_group"><?php _t('content_mgmt_group1'); ?></div>

<div class="menu_group">

<ul class="index">
	<li>
		<a href="index_item.php#content" title="<?php _t('content_mgmt'); ?>"><img src="img/icon_item.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_item.php#content" title="<?php _t('content_mgmt'); ?>" class="title"><?php _t('content_mgmt'); ?></a>
		<span class="info"><?php _t('content_mgmt_info'); ?></span>
	</li>
		<li>
		<a href="index_menu.php#content" title="<?php _t('menu_mgmt'); ?>"><img src="img/icon_menu.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_menu.php#content" title="<?php _t('menu_mgmt'); ?>" class="title"><?php _t('menu_mgmt'); ?></a>
		<span class="info"><?php _t('menu_mgmt_info'); ?></span>
	</li>
	<li>
		<a href="index_gallery.php#content" title="<?php _t('gallery_mgmt'); ?>"><img src="img/icon_gallery.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_gallery.php#content" title="<?php _t('gallery_mgmt'); ?>" class="title"><?php _t('gallery_mgmt'); ?></a>
		<span class="info"><?php _t('gallery_mgmt_info'); ?></span>
	</li>

	<li>
		<a href="index_promotion.php#content" title="<?php _t('promotion_mgmt'); ?>"><img src="img/icon_promotion.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_promotion.php#content" title="<?php _t('promotion_mgmt'); ?>" class="title"><?php _t('promotion_mgmt'); ?></a>
		<span class="info"><?php _t('promotion_mgmt_info'); ?></span>
	</li>

	<li>
		<a href="index_opinions.php#content" title="<?php _t('opinions_mgmt'); ?>"><img src="img/icon_opinions.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_opinions.php#content" title="<?php _t('opinions_mgmt'); ?>" class="title"><?php _t('opinions_mgmt'); ?></a>
		<span class="info"><?php _t('opinions_mgmt_info'); ?></span>
	</li>
	<li style="display: none;">
		<a href="index_contact_forms.php#content" title="<?php _t('contact_forms_mgmt'); ?>"><img src="img/icon_forms.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_contact_forms.php#content" title="<?php _t('contact_forms_mgmt'); ?>" class="title"><?php _t('contact_forms_mgmt'); ?></a>
		<span class="info"><?php _t('contact_forms_mgmt_info'); ?></span>
	</li>
	
	<li>
		<a href="index_ftp.php#content" title="<?php _t('ftp_mgmt'); ?>"><img src="img/icon_ftp.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_ftp.php#content" title="<?php _t('ftp_mgmt'); ?>" class="title"><?php _t('ftp_mgmt'); ?></a>
		<span class="info"><?php _t('ftp_mgmt_info'); ?></span>
	</li>

    <li style="display: none;">
        <a href="index_code_blocks.php#content" title="<?php _t('code_blocks_mgmt'); ?>"><img src="img/icon_forms.gif" width="64" height="64" alt="" border="0" /></a>
        <a href="index_code_blocks.php#content" title="<?php _t('code_blocks_mgmt'); ?>" class="title"><?php _t('code_blocks_mgmt'); ?></a>
        <span class="info"><?php _t('code_blocks_mgmt_info'); ?></span>
    </li>
    
</ul>
<div class="space"></div>
</div>

<?php if (_sec_authorised(ACCESS_MIN_ADMIN)): ?>
<br />

<div class="head_group"><?php _t('content_mgmt_group2'); ?></div>
<div class="menu_group">

<ul class="index">	

<?php if (_sec_authorised(ACCESS_MIN_ADMIN)): ?>
	<li>
		<a href="index_user.php#content" title="<?php _t('user_mgmt'); ?>"><img src="img/icon_user.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_user.php#content" title="<?php _t('user_mgmt'); ?>" class="title"><?php _t('user_mgmt'); ?></a>
		<span class="info"><?php _t('user_mgmt_info'); ?></span>
	</li>
<?php endif ?>
<?php if (_sec_authorised(ACCESS_MIN_SUPERADMIN)): ?>
	<li>
		<a href="index_config_value.php#content" title="<?php _t('config_value_mgmt'); ?>"><img src="img/icon_config_value.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_config_value.php#content" title="<?php _t('config_value_mgmt'); ?>" class="title"><?php _t('config_value_mgmt'); ?></a>
		<span class="info"><?php _t('config_value_mgmt_info'); ?></span>
	</li>
<?php endif ?>
<?php if (_sec_authorised(ACCESS_MIN_SUPERADMIN)): ?>
	<li style="display: none;">
		<a href="index_trans.php#content" title="<?php _t('trans_mgmt'); ?>"><img src="img/icon_trans.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_trans.php#content" title="<?php _t('trans_mgmt'); ?>" class="title"><?php _t('trans_mgmt'); ?></a>
		<span class="info"><?php _t('trans_mgmt_info'); ?></span>
	</li>
	
	<li class="_newline">
		<a href="index_template.php#content" title="<?php _t('template_mgmt'); ?>"><img src="img/icon_template.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_template.php#content" title="<?php _t('template_mgmt'); ?>" class="title"><?php _t('template_mgmt'); ?></a>
		<span class="info"><?php _t('template_mgmt_info'); ?></span>
	</li>
	<li style="display: none;">
		<a href="index_log.php#content" title="<?php _t('log_mgmt'); ?>"><img src="img/icon_log.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_log.php#content" title="<?php _t('log_mgmt'); ?>" class="title"><?php _t('log_mgmt'); ?></a>
		<span class="info"><?php _t('log_mgmt_info'); ?></span>
	</li>
	<li style="display: none;">
		<a href="index_repo.php#content" title="<?php _t('repo_mgmt'); ?>"><img src="img/icon_repo.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_repo.php#content" title="<?php _t('repo_mgmt'); ?>" class="title"><?php _t('repo_mgmt'); ?></a>
		<span class="info"><?php _t('repo_mgmt_info'); ?></span>
	</li>
	<li style="display: none;">
		<a href="index_register.php#content" title="<?php _t('register_mgmt'); ?>"><img src="img/icon_register.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_register.php#content" title="<?php _t('register_mgmt'); ?>" class="title"><?php echo _t('register_mgmt'); ?></a>
		<span class="info"><?php _t('register_mgmt_info'); ?></span>
	</li>
<?php endif ?>
</ul>	

<br />
<div class="space"></div>
</div>

<?php endif ?>

<?php if (_sec_authorised(ACCESS_MIN_ADMIN)): ?>
<br />





<!--  grupa "U�ytkownicy serwisu www" -->
<div class="head_group"><?php _t('content_mgmt_group3'); ?></div>
<div class="menu_group" >

<ul class="index">	
<!--  www user "Przeglad uzytkownikow" --> 
<?php if (_sec_authorised(ACCESS_MIN_ADMIN)): ?>
	<li>
		<a href="index_www_user#content" title="<?php _t('user_www_mgmt'); ?>"><img src="img/icon_site_user.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_www_user.php#content" title="<?php _t('user_www_mgmt'); ?>" class="title"><?php _t('user_www_mgmt'); ?></a>
		<span class="info"><?php _t('user_www_mgmt_info'); ?></span>
	</li>
<?php endif ?>
<!--  www user "Grupy u�ytkownik�w  Konfiguracja grup u�ytkownik�w strony www." --> 
<?php if (_sec_authorised(ACCESS_MIN_SUPERADMIN)): ?>
	<li>
		<a href="index_www_groups.php#content" title="<?php _t('user_www_groups_mgmt'); ?>"><img src="img/icon_site_user_group.gif" width="64" height="64" alt="" border="0" /></a>
		<a href="index_www_groups.php#content" title="<?php _t('user_www_groups_mgmt'); ?>" class="title"><?php _t('user_www_groups_mgmt'); ?></a>
		<span class="info"><?php _t('user_www_groups_mgmt_info'); ?></span>
	</li>
<?php endif ?>
<!--  www user "Lista operacji na danych" --> 
<?php if (_sec_authorised(ACCESS_MIN_SUPERADMIN)): ?>
  <li>
    <a href="index_www_history.php#content" title="<?php _t('user_www_changes'); ?>"><img src="img/icon_site_user_www_changes.gif" width="64" height="64" alt="" border="0" /></a>
    <a href="index_www_history.php#content" title="<?php _t('user_www_changes'); ?>" class="title"><?php _t('user_www_changes'); ?></a>
    <span class="info"><?php _t('user_www_changes_info'); ?></span>
  </li>
<?php endif ?>

<?php if (_sec_authorised(ACCESS_MIN_ADMIN)): ?>
    <li>
        <a href="index_newsletter.php#content" title="<?php _t('newsletter'); ?>"><img src="img/icon_newsletter.gif" width="64" height="64" alt="" border="0" /></a>
        <a href="index_newsletter.php#content" title="<?php _t('newsletter'); ?>" class="title"><?php _t('newsletter'); ?></a>
        <span class="info"><?php _t('newsletter_mgmt_info'); ?></span>
    </li>
<?php endif ?>

</ul>   
<br />
<div class="space"></div>
</div>
<br />




<!--  grupa "og�oszenia" -->
<div class="head_group" style="display: none;"><?php _t('content_mgmt_group4'); ?></div>
<div class="menu_group" style="display: none;">

<ul class="index"> 

<!--  grupa "Grupy og�osze�" --> 
<?php if (_sec_authorised(ACCESS_MIN_SUPERADMIN)): ?>
  <li>
    <a href="index_notice_groups.php#content" title="<?php _t('notice_groups_mgmt'); ?>"><img src="img/icon_notice_groups_mgmt.gif" width="64" height="64" alt="" border="0" /></a>
    <a href="index_notice_groups.php#content" title="<?php _t('notice_groups_mgmt'); ?>" class="title"><?php _t('notice_groups_mgmt'); ?></a>
    <span class="info"><?php _t('notice_groups_mgmt_info'); ?></span>
  </li>
<?php endif ?>


<!--  grupa "Przeglad od�oszen po uzytkowniku" -->
<?php if (_sec_authorised(ACCESS_MIN_ADMIN)): ?>
  <li>
    <a href="index_notice#content" title="<?php _t('notice_by_user_mgmt'); ?>">
		<img src="img/notice_by_user_mgmt.gif" width="64" height="64" alt="" border="0" /></a>
    <a href="index_notice.php#content" title="<?php _t('notice_by_user_mgmt'); ?>" class="title">
		<?php _t('notice_by_user_mgmt'); ?></a>
    <span class="info"><?php _t('notice_by_user_mgmt_info'); ?></span>
  </li>
<?php endif ?>



<!--  grupa "Przeglad od�oszen w grupach" -->
<?php if (_sec_authorised(ACCESS_MIN_SUPERADMIN)): ?>
  <li>
    <a href="index_notice_g.php#content" title="<?php _t('notice_by_group_mgmt'); ?>">
		<img src="img/notice_by_group_mgmt.gif" width="64" height="64" alt="" border="0" /></a>
    <a href="index_notice_g.php#content" title="<?php _t('notice_by_group_mgmt'); ?>" class="title">
		<?php _t('notice_by_group_mgmt'); ?></a>
    <span class="info"><?php _t('notice_by_group_mgmt_info'); ?></span>
  </li>
<?php endif ?>
</ul> 
<br />
<div class="space"></div>
</div>

<br />

<!--  grupy "katalog www" -->
<div class="head_group" style="display: none;"><?php _t('content_mgmt_group5'); ?></div>
<div class="menu_group"  style="display: none;">



<ul class="index"> 

<?php if (_sec_authorised(ACCESS_MIN_SUPERADMIN)): ?>
  <li>
    <a href="index_www_catalog_groups.php#content" title="<?php _t('www_catalog_groups_mgmt'); ?>"><img src="img/icon_www_catalog_groups_mgmt.gif" width="64" height="64" alt="" border="0" /></a>
    <a href="index_www_catalog_groups.php#content" title="<?php _t('www_catalog_groups_mgmt'); ?>" class="title"><?php _t('www_catalog_groups_mgmt'); ?></a>
    <span class="info"><?php _t('www_catalog_groups_mgmt_info'); ?></span>
  </li>
<?php endif ?>

<?php if (_sec_authorised(ACCESS_MIN_SUPERADMIN)): ?>
  <li>
    <a href="index_www_catalog.php#content" title="<?php _t('www_catalog_mgmt'); ?>"><img src="img/icon_www_catalog_mgmt.gif" width="64" height="64" alt="" border="0" /></a>
    <a href="index_www_catalog.php#content" title="<?php _t('www_catalog_mgmt'); ?>" class="title"><?php _t('www_catalog_mgmt'); ?></a>
    <span class="info"><?php _t('www_catalog_mgmt_info'); ?></span>
  </li>
<?php endif ?>

</ul> 

<br />
<div class="space"></div>

</div>
<br />
<?php endif ?>