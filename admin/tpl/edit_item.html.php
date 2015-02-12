<?php 
if (!defined('_APP')) {
  exit;
}
?>

	<div class="oper">
		<a href="javascript:remove()" title="<?php _t('item_delete'); ?>" class="delete"><img src="img/icon_item_delete_m.gif" width="20" height="20" alt="" border="0" /><?php _t('item_delete'); ?></a>
	</div>
<div class="history">
	<?php
	if ($ID > 0) {
		?>
		<img src="img/icon_item_edit.gif" width="64" height="64" border="0" alt="" /> 
		<?php
	} else {
		?>
		<img src="img/icon_item_add.gif" width="64" height="64" border="0" alt="" /> 
		<?php
	}
	?>
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<a href="index_item.php?menu_id=<?php echo intval($MenuID); ?><?php echo $ID > 0 ? '#i_' . intval($ID) : '#content'; ?>" title="<?php _t('content_mgmt'); ?>"><?php _t('content_mgmt'); ?></a>
	<?php _t('item_edit');
	echo ' (' . $ID . ' - ' . $Tab['item_name'] . ')'; 	
	if (isset($Message) && $Message != '') {
		?>
		<div class="message">
			<?php echo $Message; ?>
		</div>
		<?php
	}
	?>
</div>

<?php
if ($ID > 0) {
	?>

	<script type="text/javascript">
function odznacz_i(ii, i) 
{
  if (!ii.checked) {
    var zbychu = $$('input[rel=' + i + ']');
    for (i in zbychu) {
      zbychu[i].checked = false;
    }
  }
}

	function remove() 
	{
		if (confirm('<?php addslashes(_t('item_delete_confirm')); ?>')) {
			document.deleteFrm.submit();
		}
	}
	</script>
	<?php
	_gui_form_start('deleteFrm','','post',false);
	_gui_hidden('cmd','delete');
	_gui_hidden('item_id',intval($ID));
	_gui_hidden('menu_id',intval($MenuID));
	_gui_form_end(false);

	_gui_stats($Stats);
	
}	

_gui_form_start('editFrm','','post');
	_gui_hidden('cmd','edit');
	_gui_hidden('item_id',intval($ID));
	
	?>
	<br />
	
	<div id="left_tab_menu"> 
	
	<script type="text/javascript">
	 function isInt(x) 
	 { 
   		var y = parseInt(x); 
   		if (isNaN(y)) {
   			return false;
   		} 
   		return x == y && x.toString() == y.toString(); 
 	 }
 
	function open_tab(id) 
	{
	
		var tabs_link = new Array(1, 2, 3, 4, 5, 6);
		for (var i in tabs_link ) {
			if (isInt(tabs_link[i]) && tabs_link[i] != id) {
				var obiekt = document.getElementById('link_tab_' + tabs_link[i]); 
				if (obiekt != null) {
					obiekt.className = '';
				}
			}
		}
		
		document.getElementById('link_tab_' + id).className = 'activetab';
		
		var tabs = new Array(1, 2, 3, 4, 5, 6, 7);
		document.getElementById('tab_' + id).style.display = 'block';
		for (var i in tabs) {
			if (isInt(tabs[i]) && tabs[i] != id) {
				var obiekt = document.getElementById('tab_' + tabs[i]); 
				if (obiekt != null) {
					obiekt.style.display = 'none';
				}
			}
		}
		if (id == 3) {
			Sortable.create('mod_list', {handle:'mod_icon',scroll:window,onUpdate: updateOrderModule });
		}
			document.getElementById("global_cancel").removeAttribute("disabled");
			document.getElementById("global_submit").removeAttribute("disabled");
		
	}
	</script>
	
	<a href="#" id="link_tab_1" onclick="open_tab(1); return false">Definicja</a>
	<a href="#" id="link_tab_2" onclick="open_tab(2); return false">Widoczność</a>
	<a href="#" id="link_tab_3" onclick="open_tab(3); return false">Treść</a>
	<a href="#" id="link_tab_4" onclick="open_tab(4); return false">Opcje artykułu</a>
	<a href="#" id="link_tab_5" onclick="open_tab(5); return false">Grupy</a>
	<a href="#" id="link_tab_6" onclick="open_tab(6); return false">Dodatkowe parametry</a>	
		
<script type="text/javascript">

	function show_endlessClick() 
	{
		var f = document.getElementById('show_endless');
		var div = document.getElementById('show_end_div');
		if (f.checked) {
			div.style.display='none';
		} else {
			div.style.display='block';
		}
	}
	
	function promoClick(x) 
	{
		var f = document.getElementById('promo_'+x);
		var div = document.getElementById('promo_'+x+'_dates');
		if (f.checked) {
			div.style.display='block';
		} else {
			div.style.display='none';
		}
	}
	
	function promo_endlessClick(x) 
	{
		var f = document.getElementById('promo_'+x+'_endless');
		var div = document.getElementById('promo_'+x+'_enddate');
		if (f.checked) {
			div.style.display='inline';
		} else {
			div.style.display='none';
		}
	}
	
	function addModule() 
	{
		var n = document.getElementById('mod_name');
		var t = document.getElementById('mod_type');
	    if(n.value == '')
	    	n.value = t.options[t.selectedIndex].text; 		
		if (n.value!='' && t.value>0) {
			updateModuleList(n.value,t.value);
			n.value='';
		}		
	}
	
	function updateModuleList(a, b) 
	{
		var x = new Ajax.Updater("mod_list", "add_article_module.php", {
			method: "post",
			parameters: { mod_name: a, mod_type: b,item_id:<?php echo intval($ID); ?> },
			onComplete: function() {
				addOnClickHandlers(); //submodal.js
				Sortable.create('mod_list', {handle:'mod_icon',scroll:window,onUpdate: updateOrderModule });
			}
			
		});
	}
	
	Sortable.create('mod_list', {handle:'mod_icon',scroll:window,onUpdate:updateOrderModule });
	
	</script>
	<script type="text/javascript">
		function popup_submit()
		{
		  try {
  			if (document.getElementById('html_text')) {
  				tinyMCE.execCommand('mceFocus', false, 'html_text');          
  			 	tinyMCE.execCommand('mceRemoveControl', false, 'html_text');
  			}
  			if (document.getElementById('image_description')) {
  			  tinyMCE.execCommand('mceFocus', false, 'image_description');
  				tinyMCE.execCommand('mceRemoveControl', false, 'image_description');
  			}
			}
			catch (err) {}
			$('editFrm').request({
			method: 'post',
			onComplete: function() { 
				 document.getElementById('tab_7').style.display = 'none';
				 document.getElementById('tab_3').style.display = 'block';
				 document.getElementById('global_btn').style.display = 'block';
				 document.getElementById("global_cancel").removeAttribute("disabled");
				 document.getElementById("global_submit").removeAttribute("disabled");
				 document.getElementById("global_cancel").className = 'btn';
				 document.getElementById("global_submit").className = 'btn';
				 updateModuleList('', 0);
				 }
			});
			document.getElementById("editFrm").action = "";
			return false;
		}
		
		function popup_cancel()
		{
				 document.getElementById('tab_7').style.display = 'none';
				 document.getElementById('tab_3').style.display = 'block';
				 document.getElementById("global_cancel").removeAttribute("disabled");
				 document.getElementById("global_submit").removeAttribute("disabled");
				 document.getElementById('global_btn').style.display = 'block';
				 document.getElementById("global_cancel").className = 'btn';
				 document.getElementById("global_submit").className = 'btn';
				 
				 document.getElementById("editFrm").action = "";
				 if (document.getElementById('html_text')) {
				 	tinyMCE.execCommand('mceRemoveControl', false, 'html_text');
				 }
				 if (document.getElementById('image_description')) {
				 	tinyMCE.execCommand('mceRemoveControl', false, 'html_text');
				 }
				 
				 updateModuleList('', 0);
			return false;
		}
	
		function popup_url(url)
		{
    		var x = new Ajax.Updater("tab_7", url, {
    			method: "get",
    			onComplete: function() {
					document.getElementById('tab_7').style.display = 'block';
					document.getElementById('tab_3').style.display = 'none';
					document.getElementById("global_cancel").setAttribute("disabled","disabled");
					document.getElementById("global_submit").setAttribute("disabled","disabled");
					document.getElementById('global_btn').style.display = 'none';
					document.getElementById("global_cancel").className = 'btn_disabled';
				 document.getElementById("global_submit").className = 'btn_disabled';
					
					document.getElementById("editFrm").action = "edit_module.php";
					document.getElementById('emu').value = url;
					
					if (document.getElementById('html_text')) {
						tinyMCE.init({
        				mode : "none",
        				language : "en",
        				theme : "advanced",
        				plugins : "style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,pagebreak",//imagemanager,filemanager",
        				theme_advanced_buttons1_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator,fontselect,fontsizeselect",
        				theme_advanced_buttons3_add_before : "tablecontrols,separator",
        				theme_advanced_toolbar_location : "top",
        				theme_advanced_toolbar_align : "left",
        				theme_advanced_buttons2_add : "media",
        				relative_urls : false,
        				remove_script_host : false
        			});
        					tinyMCE.execCommand('mceAddControl', false, 'html_text');

					}
					
					if (document.getElementById('image_description')) {
					tinyMCE.init({
        				mode : "none",
        				language : "en",
        				language : "en",
        				theme : "advanced",
        				theme_advanced_buttons1 : "bold,italic,underline,separator,bullist,numlist,undo,redo,link,unlink,separator,code,fontselect,fontsizeselect",
        				theme_advanced_buttons2 : "",
        				theme_advanced_buttons3 : "",
        				theme_advanced_toolbar_location : "top",
        				theme_advanced_toolbar_align : "left",
        				extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
        				relative_urls : false,
        				remove_script_host : false
        			});
        					tinyMCE.execCommand('mceAddControl', false, 'image_description');
					}
					
    			}
    			
    		});
		}
		
		function getFilesFromGroup(id)
	{
		if($("group_files__"+id).getStyle('display')=='block') {
			$("group_files__"+id).setStyle({
				display: 'none'				
			});
						document.getElementById("grupka_"+id).removeAttribute("disabled");
		} else {		
		$("group_files__"+id).setStyle({
			display: 'block',
			backgroundColor: '#900',
			fontSize: '12px'
		});		
		document.getElementById("grupka_"+id).setAttribute("disabled","disabled");
		document.getElementById("grupka_"+id).checked = false;
		}
	}	
		</script>
	<input type="hidden" id="emu" name="edit_module_url" value=""/>
	</div>
	
	<div id="right_tab_content"> 
	
	<div id="tabView">
		<div id="tab_1" class="dhtmlgoodies_aTab">
	<?php require 'edit_item_base.html.php'; ?>
		</div>
		
		<div id="tab_2" class="dhtmlgoodies_aTab">
			<?php
			_gui_checkbox('active',$T['item_active'],1,$Tab['active']>0,'',$T['item_active_info']);
			_gui_date('show_start',$T['item_show_start'],substr($Tab['show_start'],0,16),true,false,$Error['show_start'],$T['item_show_start_info']);
			_gui_checkbox('show_endless',$T['item_show_endless'],1,$Tab['show_endless']>0,'',$T['item_show_endless_info'],array('onclick'=>'show_endlessClick()'));
			
			_gui_block_start('show_end_div',($Tab['show_endless']>0));
				_gui_date('show_end',$T['item_show_end'],substr($Tab['show_end'],0,16),true,false,$Error['show_end'],$T['item_show_end_info']);
			_gui_block_end();
			
			_gui_select('access_level',$T['item_access_level'],$Tab['access_level'],$AccessLevel,'','',false,$Error['access_level'],$T['item_access_level_info']); //access_level
			_gui_break();
			_gui_checkbox('hide_in_map',$T['item_show_in_map'],1,$Tab['hide_in_map']>0,'',$T['item_show_in_map_info']);
			_gui_checkbox('hide_in_menu',$T['item_show_in_menu'],1,$Tab['hide_in_menu']>0,'',$T['item_show_in_menu_info']);
			_gui_checkbox('hide_in_subitems',$T['item_show_in_subitems'],1,$Tab['hide_in_subitems']>0,'',$T['item_show_in_subitems_info']);
			_gui_checkbox('show_created',$T['item_show_created'],1,$Tab['show_created']>0,'',$T['item_show_created_info']); // pokazuj date utworzenia
			_gui_checkbox('show_modificated',$T['item_show_modificated'],1,$Tab['show_modificated']>0,'',$T['item_show_modificated_info']); // pokazu date modyfikacji
			_gui_checkbox('show_author',$T['item_show_author'],1,$Tab['show_author']>0,'',$T['item_show_author_info']); 
			
			?>
		</div>

		<div id="tab_3" class="dhtmlgoodies_aTab">
		
			<?php
			// przycisk - dodaj nowy moduł
			
			//lista modułów artykułu - przy każdym - zmiana kolejności, edytuj, usuń
			//_gui_form_row(false);
			
			echo '<div class="global_top2">';
				_t('item_mod_name');
				?>
				<input type="text" class="in" style="width: 150px" value="" maxlength="255" name="mod_name" id="mod_name" />&nbsp;
				<select name="mod_type" class="in" id="mod_type">
				<?php
				foreach ($GL_MOD_TYPE as $k => $v) {
					echo '<option value="' . intval($k) . '">' . htmlspecialchars($T[$v->name]) . '</option>';
				}
				?>
				</select>
				<?php
				_gui_button($T['item_module_add'], 'addModule()');
				
			//_gui_form_row_end(false);
			
			echo '</div>';

			$Modules = $Tab['article']['content'];
			$ArticleID = $Tab['article']['article_id'];
			_gui_hidden('article_id', intval($ArticleID));
			
			?>
			
			<ul id="mod_list">
				<?php
				//var_dump($Tab['article']);
				require 'list_article_module.php';
				?>
			</ul>
		</div>

		<div id="tab_4" class="dhtmlgoodies_aTab">
			<?php
			_gui_textarea('article[meta_description]',$T['item_meta_description'],$Tab['article']['meta_description'],30,3,WYSIWYG_NONE,false,'',$T['item_meta_description_info']); //widoczne jesli link out
			_gui_textarea('article[meta_keywords]',$T['item_meta_keywords'],$Tab['article']['meta_keywords'],30,3,WYSIWYG_NONE,false,'',$T['item_meta_keywords_info']); //widoczne jesli link out
			_gui_textarea('article[tags]',$T['item_tags'],$Tab['article']['tags'],30,3,WYSIWYG_NONE,false,'',$T['item_tags_info']); //widoczne jesli link out
			_gui_break();
			if (is_null($Tab['article']['template_id']) || empty($Tab['article']['template_id']) || $Tab['article']['template_id'] == 0) {
			  foreach ($Templates as $k => $V) {
			    if ($k == 0) {
			      continue;
			    }
			    $Tab['article']['template_id'] = $k;
			    break;
			  }
			}
			_gui_select('article[template_id]', $T['item_template_id'], $Tab['article']['template_id'], $Templates, '', 'template_id_txt_func', false, $Error['template_id'], $T['item_template_id_info']); //access_level
				function template_id_txt_func($k, $v) 
				{
					global $T;
					if ($k > 0) {
						return htmlspecialchars($v['template_name']);
					} else {
						return $v;
					}
				}
			_gui_break();
			//_gui_checkbox('article[show_author]',$T['item_show_author'],1,$Tab['article']['show_author']>0,'',$T['item_show_author_info']);
			_gui_text('article[author]',$T['item_author'],$Tab['article']['author'],255,false,'',$T['item_author_info']); //widoczne jesli link out
			?>
			<div class="row">
<div class="row_left">
<label for="article[author_source]">zródło:</label>
</div>
<div class="row_right">
<input type="text" class="in" name="article[author_source]" value="<?php echo $Tab['article']['author_source'] ?>" style="margin-bottom: 3px;"/> (pełen URL)

<input type="text" class="in" name="article[author_source_name]" value="<?php echo $Tab['article']['author_source_name'] ?>"/> (krótki adres - link)
</div>
</div>
			<?php
			_gui_text('article[order]',$T['item_article_order'],$Tab['article']['order'],255,false,'',$T['item_order_info']); //widoczne jesli link out
			?>
		</div>
		<div id="tab_5" class="dhtmlgoodies_aTab">
			<?php
			//pobierz widoczne grupy - najpierw te z terminami
			//potem klasyfikator...
			if (count($promotions) > 0) {
				foreach ($promotions as $k => $v) {
					_gui_form_row2();					
						?>
						<label for="promo_<?php echo intval($k); ?>">
						<input type="checkbox" name="promo[]" value="<?php echo intval($k); ?>" 
						class="in check" id="promo_<?php echo intval($k); ?>" <?php echo is_array($Tab['group'][$k])?' checked ':''; ?> 
						onclick="promoClick(<?php echo intval($k); ?>)" />&nbsp;<?php echo htmlspecialchars($v['name']); ?></label>
						<?php
					_gui_form_row_mid();
						?>
						<div style="margin: 0px 10px 0px 5px;; display:<?php echo is_array($Tab['group'][$k])?'block':'none' ?>;" 
						id="promo_<?php echo intval($k); ?>_dates" >
							<?php
							_t('from');
							_gui_datefield('promo_'.intval($k).'_start','promo_'.intval($k).'_start',$Tab['group'][$k]['date_start']);
							?>
							<div style="width: 40px; padding:0px 5px 0px 10px; display: inline;">
							<?php
							if ($v['allow_endless'] > 0) {
								?>
								<label for="promo_<?php echo intval($k); ?>_endless"><input type="checkbox" id="promo_<?php echo intval($k); ?>_endless" name="promo_<?php echo intval($k); ?>_endless" value="1" class="in check" onclick="promo_endlessClick(<?php echo intval($k); ?>)" <?php echo $Tab['group'][$k]['date_endless'] > 0 ? ' checked ' : '';?> /></div>
								<?php
							} else {
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;</div>
								<?php
							}
							_t('to');
							if ($v['allow_endless'] > 0) {
								?>
								</label><div id="promo_<?php echo intval($k); ?>_enddate" style="margin: 0px; padding:0px; display: <?php echo $Tab['group'][$k]['date_endless'] > 0 ? 'inline' : 'none';?>;">
								<?php
							}
							_gui_datefield('promo_'.intval($k).'_end','promo_'.intval($k).'_end',$Tab['group'][$k]['date_end']);
							if ($v['allow_endless'] > 0) {
								?>
								</div>
								<?php
							}
							?>
						</div>
						<?php
					_gui_form_row_end();
				}
				_gui_break();
			}
			if (count($groups) > 0) {
				$x=0;
				_gui_form_row();
				_t('item_classifier');
				_gui_form_row_mid();
				foreach ($groups as $k=>$v) {
					?>
					<label for="group_<?php echo intval($k); ?>" style="white-space: nowrap; margin: 0px 15px 8px 0px; width: 230px; display: block; float: left;">
					<input type="checkbox" name="groups[]" value="<?php echo intval($k); ?>" class="in check" id="group_<?php echo intval($k); ?>" 
					<?php echo isset($Tab['group'][$k])?' checked ':''; ?> />&nbsp;<?php echo htmlspecialchars($v['name']); ?></label>
					<?php
					++$x;
				}
				_gui_form_row_end();
			}
			?>
		</div>
		<?php
		//var_dump($GL_CONF);
		$show_addons=false;
		for ($i=0;$i<ADDONS_COUNT;++$i) {
			if (trim($GL_CONF['ADDONS']['ADDON'.$i.'_NAME'])!='') {
				if (!$show_addons)  {
					?>
					<div id="tab_6" class="dhtmlgoodies_aTab">
					<?php
				}
				$show_addons=true;
				_gui_text('addon[add'.$i.']',$GL_CONF['ADDONS']['ADDON'.$i.'_NAME'],$Tab['addon']['add'.$i],255,false,'',$GL_CONF['ADDONS']['ADDON'.$i.'_INFO']); //widoczne jesli link out
			}
		}
		if ($show_addons) {
			?>
			</div>
			<?php
		} else {
		  ?>
		  <div id="tab_6" class="dhtmlgoodies_aTab">
		  Nie ma żadnych zmiennych.
		  </div>
		  <?php
		}
		?>
		
		<div id="tab_7" class="dhtmlgoodies_aTab">
		
		</div>

<div class="space"></div>

	</div> <!-- END tab content -->
		<?php
		
	$url_b = 'location.href=\'index_item.php?menu_id='.intval($MenuID).($ID > 0 ? '#i_'.intval($ID) : '#content').'\'';
	?>
	<div id="global_btn">
				<input type="button" onclick="<?php echo $url_b; ?>" id="global_cancel" value="<?php echo htmlspecialchars($T['cancel']); ?>" class="btn" />
				<input type="button" onclick="<?php echo 'document.'.htmlspecialchars('editFrm').'.submit()'; ?>" id="global_submit" value="<?php echo htmlspecialchars($T['ok']); ?>" class="btn" />
	</div> 

	</div> <!--  END right_tab_content-->
<?php
	//_gui_form_row_end();
_gui_form_end();
?>
<div class="space">&nbsp;</div>
<script type="text/javascript">
open_tab(1);
</script>