<?php

if (!defined('_APP')) {  exit;}
if (defined('_CONFIG__ADMIN.PHP')) {  return;}

define('_CONFIG__ADMIN.PHP', 1);
define('USER_SQL_ENCRYPTED', 1);
define('DEBUG', 1);

define('ADMIN_PATH', 'http://www.yanser.performer.pl/admin/'); //sciezka do admina (bezwzgl??dna - http: lub https:)
define('ADMIN_HTTPS', (strpos(ADMIN_PATH,'https://')===0)); //czy uĹĽywaÄ‡ poĹ‚Ä…czeĹ„ szyfrowanych
define('ADMIN_LANG', 'polski');//jezyk panelu administracyjnego

define('ADMIN_DEF_CONFIG_ICON', 'img/icon_config_value_m.gif');
define('ADMIN_CODE_REGEX', '/^([a-zA-Z_])+([a-zA-Z0-9_])*$/');

define('ADMIN_TITLE', 'YANSER  - CMS Performer');
define('ADMIN_FOOTER_TEXT', 'CMS Performer &copy; 2014 <a href="mailto:marcin@performer.pl" title="CMS Perforer">www.performer.pl</a>, tel: 600 356 384.');

define('TEMPLATE_DIR', 'templates');
define('TEMPLATE_FILE_VIEW', 'index.html.php');
define('TEMPLATE_FILE_CTRL', 'index.php');

define('LVL_EDITOR', 4);
define('LVL_REDACTOR', 8);
define('LVL_ADMIN', 16);
define('LVL_SUPERADMIN', 32);

$GL_ACCESS_LVL = array(
	LVL_EDITOR=>'Edytor',
	LVL_REDACTOR=>'Redaktor',
	LVL_ADMIN=>'Administrator',
	LVL_SUPERADMIN=>'Superadmin'
);

define('ACCESS_ALL', 65535);
define('ACCESS_MIN_SUPERADMIN', 65535 - 31);
define('ACCESS_MIN_ADMIN', 65535 - 15);
define('ACCESS_MIN_REDACTOR', 65535 - 7);
define('ACCESS_MIN_EDITOR', 65535 - 3);

define('GUI_YES_IMG', '<img src="img/yes.gif" alt="tak"  vspace="5" hspace="5" />');
define('GUI_NO_IMG', '<img src="img/no.gif" alt="nie"  />');
define('ADDONS_COUNT', 10);

define('NF_DEC', 2);
define('NF_DEC_SEP', ',');
define('NF_K_SEP',' ');

/*
 $GL_LANG = array(
 'polski','english'
 );
 */
define('ITEM_ARTICLE', '0');
define('ITEM_MIRROR', '1');
define('ITEM_LINK_IN', '2');
define('ITEM_LINK_OUT', '3');
define('ITEM_COPY', '4');

$GL_ITEM_TYPE = array(
	ITEM_ARTICLE => array(
			'name'=>'item_article',
			'icon'=>'img/item_article.gif',
			'small_icon'=>'img/item_article_m.gif',
	),
	ITEM_MIRROR => array(
			'name'=>'item_mirror',
			'icon'=>'img/item_mirror.gif',
			'small_icon'=>'img/item_mirror_m.gif',
	),
	ITEM_LINK_IN => array(
			'name'=>'item_link_in',
			'icon'=>'img/item_link_in.gif',
			'small_icon'=>'img/item_link_in_m.gif',
	),
	ITEM_LINK_OUT => array(
			'name'=>'item_link_out',
			'icon'=>'img/item_link_out.gif',
			'small_icon'=>'img/item_link_out_m.gif',
	),
	ITEM_COPY => array(
					'name'=>'item_copy',
					'icon'=>'img/item_article.gif',
					'small_icon'=>'img/item_article_m.gif',
	),
);

define('NSTEP_NEXT', 0);
define('NSTEP_SAME', 1);
define('NSTEP_PREV', 2);

$GL_ITEM_NSTEPS = array(
  NSTEP_NEXT => 'item_step_edit',
  NSTEP_SAME => 'item_step_add',
  NSTEP_PREV => 'item_step_menu',
);

define('LINK_TARGET_IFRAME', 3);
define('LINK_TARGET_INCLUDE', 4);
define('LINK_TARGET_SAME', 0);
define('LINK_TARGET_BLANK', 1);
define('LINK_TARGET_POPUP', 2);

$GL_LINK_TARGET = array(
  LINK_TARGET_SAME => 'target_same',
  LINK_TARGET_BLANK => 'target_blank',
  LINK_TARGET_POPUP => 'target_popup',
);

define('MOD_TEXT', 1); // text html
define('MOD_GALLERY', 2); //galeria zdjec
define('MOD_CONTACT', 3); //formularz kontaktowy
define('MOD_DOWLOAD', 4); // pliki do pobrania
define('MOD_FORUM', 5); // komentarze
define('MOD_SUBITEMS', 6); //subitemy
define('MOD_INCLUDE', 7); // include
define('MOD_SHORTCUT', 8); //skrot
define('MOD_AUTHOR', 9);//autor
define('MOD_DATE', 10);//data publikacji artykulu == date_start
define('MOD_VOTE', 11); //głosowanie na artykul‚
define('MOD_ALSO', 12);//zobacz takze
define('MOD_TOC', 13);//spis tresci - albo lista stron albo lista modulow z tytulami
define('MOD_PAGEBREAK', 14);//podzial‚ strony
define('MOD_PAGENAV', 15); // nawigacja po stronach rownorzednych
define('MOD_ICON', 16); // wyswietlenie ikony itemu
define('MOD_FLASH', 17); //multimedia - osadzenie flasha na stronie
define('MOD_ITEMNAV', 18); // TODO ten moduł jest wolny
define('MOD_IMAGE', 19); // pojedyncze zdjęcie z galerii
define('MOD_SEARCH', 20); // modul wyszukiwarki
define('MOD_OPINIONS', 21); // modul opinii
define('MOD_CONTACT_FORM', 22); // modul formularzy kontaktowych, autor: JH
define('MOD_FTP', 23); // modul formularzy kontaktowych, autor: JH
define('MOD_SEPARATOR', 24);
define('MOD_SITEMAP', 25);
define('MOD_YOUTUBE', 26);
define('MOD_SHOWGROUPS', 27);
define('MOD_REGISTERUSER', 28);
define('MOD_LOGIN', 29);
define('MOD_EDIT_PROFILE', 30);
define('MOD_REGISTERNEWS', 31);
define('MOD_RECENTPOSTS', 32);
define('MOD_ADDNOTICE', 33);
define('MOD_SHOWNOTICES_TREE', 34);
define('MOD_SHOWNOTICES_TOP', 35);
define('MOD_SUBSCRIPCTION', 36); // podpis
define('MOD_CODE_BLOCK', 37); // blok kodu
define('MOD_SHOW_WWW_CATALOG_TREE', 38); // drzewko z grupami w katalogu www
define('MOD_SHOW_WWW_CATALOG_TOP', 39); // lista n najnowszych wpisów w katalogu
define('MOD_SHOW_TAGS', 40); // popularne tagi artukulow

class article_mod
{
  var $name;
  var $icon;
  var $small_icon;
  var $script;
  var $style;

  function article_mod($n, $i, $si, $scr, $stl=array())
  {
    $this->name = $n;
    $this->icon = 'img/'.$i;
    $this->small_icon = 'img/' . $si;
    $this->script = $scr;
    $this->style = $stl;
  }
}

// możliwe podawanie indeksu i nazwy w postaci 'indeks' => 'nazwa'

$GL_MOD_STYLE = array(
  MOD_TEXT => array('mod_text_style0','mod_text_style1','mod_text_style2','mod_text_style3','mod_text_style4','mod_text_style5','mod_text_style6','mod_text_style7','mod_text_style8','mod_text_style9'),
  MOD_SUBITEMS => array('mod_subitems_style0','mod_subitems_style1','mod_subitems_style2','mod_subitems_style3','mod_subitems_style4','mod_subitems_style5','mod_subitems_style6','mod_subitems_style7','mod_subitems_style8','mod_subitems_style9','mod_subitems_style10','mod_subitems_style11'),
  MOD_SHORTCUT => array(),
  //MOD_ICON => array('mod_icon_style0','mod_icon_style1','mod_icon_style2','mod_icon_style3','mod_icon_style4','mod_icon_style5','mod_icon_style6','mod_icon_style7','mod_icon_style8','mod_icon_style9'),
  MOD_GALLERY => array(0=>'mod_gallery_style0',1=>'mod_gallery_style1',2=>'mod_gallery_style2',3=>'mod_gallery_style3',4=>'mod_gallery_style4',5=>'mod_gallery_style5',6=>'mod_gallery_style6', 7=>'mod_gallery_style7', 8=>'mod_gallery_style8', 9=>'mod_gallery_style9', 10=>'mod_gallery_style10', 11=>'mod_gallery_style11', 12=>'mod_gallery_style12', 13=>'mod_gallery_style13', 14=>'mod_gallery_style14', 15=>'mod_gallery_style15', 16=>'mod_gallery_style16', 17=>'mod_gallery_style17', 18=>'mod_gallery_style18', 19=>'mod_gallery_style19'),
  MOD_ALSO => array(),
  MOD_DOWLOAD => array(),
  MOD_CONTACT => array(),
  MOD_INCLUDE => array(),
  MOD_VOTE => array('mod_vote_style0','mod_vote_style1','mod_vote_style2'),
  MOD_FORUM => array(),
  MOD_DATE => array('mod_date_style0','mod_date_style1','mod_date_style2','mod_date_style3','mod_date_style4','mod_date_style5','mod_date_style6','mod_date_style7','mod_date_style8','mod_date_style9'),
  MOD_AUTHOR => array('mod_author_style0','mod_author_style1','mod_author_style2','mod_author_style3','mod_author_style4','mod_author_style5','mod_author_style6','mod_author_style7','mod_author_style8','mod_author_style9'),
  MOD_PAGEBREAK => array(),
  MOD_PAGENAV => array('mod_page_nav_style0','mod_page_nav_style1','mod_page_nav_style2','mod_page_nav_style3','mod_page_nav_style4','mod_page_nav_style5','mod_page_nav_style6','mod_page_nav_style7','mod_page_nav_style8','mod_page_nav_style9'),
  MOD_ITEMNAV => array('mod_itemnav_style0','mod_itemnav_style1','mod_itemnav_style2','mod_itemnav_style3','mod_itemnav_style4','mod_itemnav_style5','mod_itemnav_style6','mod_itemnav_style7','mod_itemnav_style8','mod_itemnav_style9'),
  MOD_TOC => array('mod_toc_style0','mod_toc_style1','mod_toc_style2'),
  MOD_FLASH => array(),
  MOD_FTP => array('mod_ftp_style0', 'mod_ftp_style1', 'mod_ftp_style2', 'mod_ftp_style3', 'mod_ftp_style4', 'mod_ftp_style5', 'mod_ftp_style6', 'mod_ftp_style7', 'mod_ftp_style8', 'mod_ftp_style9'),
  MOD_SEPARATOR => array('mod_separator_style0', 'mod_separator_style1', 'mod_separator_style2', 'mod_separator_style3', 'mod_separator_style4', 'mod_separator_style5', 'mod_separator_style6', 'mod_separator_style7', 'mod_separator_style8', 'mod_separator_style9'),
  MOD_IMAGE => array('mod_image_style0', 'mod_image_style1', 'mod_image_style2', 'mod_image_style3', 'mod_image_style4', 'mod_image_style5', 'mod_image_style6', 'mod_image_style7', 'mod_image_style8', 'mod_image_style9', 'mod_image_style10'),
  MOD_SEARCH => array(),
  MOD_SITEMAP => array(),
  MOD_OPINIONS => array('mod_opinions_style0', 'mod_opinions_style1', 'mod_opinions_style2', 'mod_opinions_style3', 'mod_opinions_style4', 'mod_opinions_style5', 'mod_opinions_style6', 'mod_opinions_style7', 'mod_opinions_style8', 'mod_opinions_style9' ),
  MOD_CONTACT_FORM=> array('mod_contact_form_style0','mod_contact_form_style1','mod_contact_form_style2','mod_contact_form_style3','mod_contact_form_style4','mod_contact_form_style5','mod_contact_form_style6','mod_contact_form_style7','mod_contact_form_style8','mod_contact_form_style9'),
  MOD_YOUTUBE => array('mod_youtube_style0','mod_youtube_style1','mod_youtube_style2', 'mod_youtube_style3','mod_youtube_style4','mod_youtube_style5','mod_youtube_style6','mod_youtube_style7','mod_youtube_style8','mod_youtube_style9'),
  MOD_SHOWGROUPS => array('mod_showgroups_style0','mod_showgroups_style1','mod_showgroups_style2','mod_showgroups_style3','mod_showgroups_style4','mod_showgroups_style5','mod_showgroups_style6','mod_showgroups_style7','mod_showgroups_style8','mod_showgroups_style9','mod_showgroups_style10','mod_showgroups_style11'),
  MOD_REGISTERUSER => array(),
  MOD_LOGIN => array(),
  MOD_EDIT_PROFILE => array(),
  MOD_REGISTERNEWS => array(),
  MOD_RECENTPOSTS => array(),
  MOD_ADDNOTICE => array(),
  MOD_SHOWNOTICES_TREE => array(),
  MOD_SHOWNOTICES_TOP => array(),
  MOD_SHOW_WWW_CATALOG_TREE => array(),
  MOD_SHOW_WWW_CATALOG_TOP => array(),
  MOD_SHOW_TAGS => array(),
);

$GL_MOD_TYPE = array(
  MOD_TEXT => new article_mod('mod_text_title','icon_mod_text.gif','icon_mod_text_m.gif','mod_text',$GL_MOD_STYLE[MOD_TEXT]),
  MOD_SUBITEMS => new article_mod('mod_subitems_title','icon_mod_subitems.gif','icon_mod_subitems_m.gif','mod_subitems',$GL_MOD_STYLE[MOD_SUBITEMS]),
  MOD_SHORTCUT => new article_mod('mod_shortcut_title','icon_mod_shortcut.gif','icon_mod_shortcut_m.gif','mod_shortcut',$GL_MOD_STYLE[MOD_SHORTCUT]),
  //MOD_ICON => new article_mod('mod_icon_title','icon_mod_icon.gif','icon_mod_icon_m.gif','mod_icon',$GL_MOD_STYLE[MOD_ICON]),
  MOD_GALLERY => new article_mod('mod_gallery_title','icon_mod_gallery.gif','icon_mod_gallery_m.gif','mod_gallery',$GL_MOD_STYLE[MOD_GALLERY]),
  MOD_INCLUDE => new article_mod('mod_include_title','icon_mod_include.gif','icon_mod_include_m.gif','mod_include',$GL_MOD_STYLE[MOD_INCLUDE]),
  MOD_IMAGE => new article_mod('mod_image_title', 'icon_mod_image.gif', 'icon_mod_image_m.gif', 'mod_image', $GL_MOD_STYLE[MOD_IMAGE]),
  MOD_SEARCH => new article_mod('mod_search_title', 'icon_mod_search.gif', 'icon_mod_search_m.gif', 'mod_search', $GL_MOD_STYLE[MOD_SEARCH]),
  MOD_OPINIONS => new article_mod('mod_opinions_title', 'icon_mod_opinions.gif', 'icon_mod_opinions_m.gif', 'mod_opinions', $GL_MOD_STYLE[MOD_OPINIONS]),
  //MOD_CONTACT_FORM => new article_mod('mod_contact_form_title','icon_mod_contact.gif','icon_mod_contact_m.gif','mod_contact_form', $GL_MOD_STYLE[MOD_CONTACT_FORM]), // TODO:ikona jest taka sama jak w mofule MOD_CONTACT trzeba zmienić ,
  MOD_FTP => new article_mod('mod_ftp_name','icon_mod_ftp.gif','icon_mod_ftp_m.gif','mod_ftp',$GL_MOD_STYLE[MOD_FTP]),
  MOD_SEPARATOR => new article_mod('mod_separator_name','icon_mod_separator.gif','icon_mod_separator_m.gif','mod_separator',$GL_MOD_STYLE[MOD_SEPARATOR]),  
  MOD_SITEMAP => new article_mod('mod_sitemap_name','icon_mod_sitemap.gif','icon_mod_sitemap_m.gif','mod_sitemap',$GL_MOD_STYLE[MOD_SITEMAP]),
  MOD_YOUTUBE => new article_mod('mod_youtube_name','icon_mod_youtube.gif','icon_mod_youtube_m.gif','mod_youtube', $GL_MOD_STYLE[MOD_YOUTUBE]),
  MOD_SHOWGROUPS => new article_mod('mod_showgroups_name','icon_mod_showgroups.gif','icon_mod_showgroups_m.gif','mod_showgroups', $GL_MOD_STYLE[MOD_SHOWGROUPS]),
  MOD_REGISTERUSER => new article_mod('mod_registeruser_name','icon_mod_registeruser.gif','icon_mod_registeruser_m.gif','mod_registeruser', $GL_MOD_STYLE[MOD_REGISTERUSER]),
  MOD_LOGIN => new article_mod('mod_login_name','icon_mod_login.gif','icon_mod_login_m.gif','mod_login', $GL_MOD_STYLE[MOD_LOGIN]),
  //MOD_EDIT_PROFILE => new article_mod('mod_edit_profile_name','icon_mod_edit_profile.gif','icon_mod_edit_profile_m.gif','mod_edit_profile', $GL_MOD_STYLE[MOD_EDIT_PROFILE]),
  //MOD_REGISTERNEWS => new article_mod('mod_registernews_name','icon_registernews.gif','icon_mod_registernews_m.gif','mod_registernews', $GL_MOD_STYLE[MOD_REGISTERNEWS]),
  //MOD_RECENTPOSTS => new article_mod('mod_recentposts_name','icon_recentposts.gif','icon_mod_recentposts_m.gif','mod_recentposts', $GL_MOD_STYLE[MOD_RECENTPOSTS]),
  //MOD_ADDNOTICE => new article_mod('mod_addnotice_name','icon_addnotice.gif','icon_mod_addnotice_m.gif','mod_addnotice', $GL_MOD_STYLE[MOD_ADDNOTICE]),
  //MOD_SHOWNOTICES_TREE => new article_mod('mod_shownotices_tree_name','icon_shownotices_tree.gif','icon_mod_shownotices_tree_m.gif','mod_shownotices_tree', $GL_MOD_STYLE[MOD_SHOWNOTICES_TREE]),
  //MOD_SHOWNOTICES_TOP => new article_mod('mod_shownotices_top_name','icon_shownotices_top.gif','icon_mod_shownotices_top_m.gif','mod_shownotices_top', $GL_MOD_STYLE[MOD_SHOWNOTICES_TOP]),
  //MOD_SUBSCRIPCTION => new article_mod('mod_subscription_title','icon_mod_subscription.gif','icon_mod_subscription_m.gif','mod_subscription',$GL_MOD_STYLE[MOD_SUBSCRIPCTION]),
  //MOD_CODE_BLOCK => new article_mod('mod_code_block_title','icon_mod_code_block.gif','icon_mod_code_block_m.gif','mod_code_block',$GL_MOD_STYLE[MOD_CODE_BLOCK]),
  //MOD_SHOW_WWW_CATALOG_TREE => new article_mod('mod_show_www_catalog_tree_title','icon_mod_show_www_catalog_tree.gif','icon_mod_show_www_catalog_tree_m.gif','mod_show_www_catalog_tree',$GL_MOD_STYLE[MOD_SHOW_WWW_CATALOG_TREE]),
  //MOD_SHOW_WWW_CATALOG_TOP => new article_mod('mod_show_www_catalog_top_title','icon_mod_show_www_catalog_top.gif','icon_mod_show_www_catalog_top_m.gif','mod_show_www_catalog_top',$GL_MOD_STYLE[MOD_SHOW_WWW_CATALOG_TOP]),
  MOD_SHOW_TAGS => new article_mod('mod_show_tags_title','icon_mod_show_tags.gif','icon_mod_show_tags_m.gif','mod_show_tags',$GL_MOD_STYLE[MOD_SHOW_TAGS]),
  MOD_PAGENAV => new article_mod('mod_page_nav_title','icon_mod_page_nav.gif','icon_mod_page_nav_m.gif','mod_page_nav',$GL_MOD_STYLE[MOD_PAGENAV]),
);

define('WYSIWYG_SIMPLE', 1);
define('WYSIWYG_FULL', 2);
define('WYSIWYG_NONE', 0);

define('FILE_ANY', 0);//dowolny plik - do
define('FILE_ICON', 1); //ikona
define('FILE_PHOTO', 2); //zdjecie - powiekszenie
define('FILE_THUMB', 3); //pomniejszenie 1

define('REPOSITORY', realpath('../repository') . '/'); // bezwzgledna sciezka do katalogu reposytorium
define('REPO_IMAGES', REPOSITORY . 'images/'); // bezwzgledna sciezka do katalogu w ktorym przechowywane sa zrodla obrazkow
define('REPOSITORY_IMG', realpath('../images') . '/'); //bezwzgledna sciezka do katalogu docelowego obrazkow

