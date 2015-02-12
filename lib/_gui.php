<?php if(!defined('_APP')) exit; if(defined('_LIB__GUI.PHP')) return; define('_LIB__GUI.PHP',1);

function _gui_select_item_print($tab, $level, $value, $items, $self_id, $one_level=false) 
{
  $level_str = str_pad('', $level, '-');
  foreach ($tab as $k=>$v) {
    if ($k!=$self_id) {
      echo '<option value="' . intval($k) . '"';
      if ($k==$value) {
        echo ' selected ';
      }
      echo '>'.$level_str.' '.htmlspecialchars($v['item_name']).'</option>';
      if (count($items[$k]['subitems'])>0 && $one_level === false) {
        _gui_select_item_print($items[$k]['subitems'],$level+1,$value,$items,$self_id);
      }
    }
  }
}


function _gui_select_item($name,$lbl,$value=0,$self_id,$items,$allow_empty=false,$required=false,$error='',$info='', $valueid = '') {
  $id = isset($props['id'])?$props['id']:$name;
  if($error!='') {
    ?>
<div class="error"><?php echo htmlspecialchars($error); ?></div>
<div class="row_error"><?php
} else {
  ?>
<div class="row" <?=$valueid ? ' id="' . $valueid . '"' : ''?>><?php
}
?>
<div class="row_left"><label for="<?php echo htmlspecialchars($id);?>"><?php echo ($required?'* ':'').htmlspecialchars($lbl); ?></label></div>
<div class="row_right"><select
	name="<?php echo htmlspecialchars($name); ?>" class="in"
	id="<?php echo htmlspecialchars($id); ?>">

	<?php
	if($allow_empty) {
	  echo '<option value="0"';
	  if(0==$value) {
					echo ' selected ';
	  }
	  echo '> ----------------- </option>';
	}
	$level = 0;
	$item = $items[0];
	_gui_select_item_print($item['subitems'],$level,$value,$items,$self_id);
	/*foreach($data as $k=>$v) {
	 $val = $use_val_function?$val_func($k,$v):$k;
	 $txt = $use_txt_function?$txt_func($k,$v):$v;
	 echo '<option value="'.htmlspecialchars($val).'"';
	 if($val==$value) {
	 echo ' selected ';
	 }
	 echo '>'.htmlspecialchars($txt).'</option>';
	 }*/
	echo '</select>';
	if($info!='') {
	  echo '<br /><span class="info">'.htmlspecialchars($info).'</span>';
	}
	?></div>
</div>
	<?php

}

function _gui_select_item_all($name,$lbl,$value=0,$self_id,$allow_empty=false,$required=false,$error='',$info='', $valueid = '') {
  $id = isset($props['id'])?$props['id']:$name;
  if($error!='') {
    ?>
<div class="error"><?php echo htmlspecialchars($error); ?></div>
<div class="row_error"><?php
} else {
  ?>
<div class="row" <?=$valueid ? ' id="' . $valueid . '"' : ''?>><?php
}
?>
<div class="row_left"><label for="<?php echo htmlspecialchars($id);?>"><?php echo ($required?'* ':'').htmlspecialchars($lbl); ?></label></div>
<div class="row_right"><select
	name="<?php echo htmlspecialchars($name); ?>" class="in"
	id="<?php echo htmlspecialchars($id); ?>">

	<?php
	if($allow_empty) {
	  echo '<option value="0"';
	  if(0==$value) {
					echo ' selected ';
	  }
	  echo '> ----------------- </option>';
	}
	$Menus = menu_list();
	foreach($Menus as $menu) {
	  echo "<optgroup label='" . htmlspecialchars($menu['menu_name']) . "'>";
	  $items = item_tree($menu["menu_id"]);
	  $level = 0;
	  $item = $items[0];
	  if(isset($item))
	  _gui_select_item_print($item['subitems'],$level,$value,$items,$self_id);
	  echo "</optgroup>";
	}
	echo '</select>';
	if($info!='') {
	  echo '<br /><span class="info">'.htmlspecialchars($info).'</span>';
	}
	?></div>
</div>
	<?php

}

function _gui_image($name, $lbl, $value='', $type = false, $required=false, $error='', $info='', $props=array()) 
{
  $id = isset($props['id'])?$props['id']:$name;
  if($error!='') {
    ?>
<div class="error"><?php echo htmlspecialchars($error); ?></div>
<div class="row_error"><?php
} else {
  ?>
<div class="row"><?php
}
?>
<div class="row_left"><label for="<?php echo htmlspecialchars($id);?>"><?php echo ($required?'* ':'').htmlspecialchars($lbl); ?></label></div>
<div class="row_right"><input class="in" type="text"
	name="<?php echo htmlspecialchars($name); ?>"
	id="<?php echo htmlspecialchars($id); ?>"
	value="<?php echo htmlspecialchars($value); ?>"
	<?php
	if(is_array($props) && count($props)>0) {
	  foreach($props as $k=>$v) {
					if($k!='id') {
					  echo ' '.htmlspecialchars($k).'="'.htmlspecialchars($v).'"';
					}
	  }
	}
	?> /><? if($type) { ?><a
	href="list_gallery_images.php?field=<?php echo urlencode($id); ?>&type=<?php echo urlencode($type); ?>"
	class="submodal-780-600">rep</a><? } ?> <?php 
	if($info!='') {
	  echo '<br /><span class="info">'.htmlspecialchars($info).'</span>';
	}
	?></div>
</div>

	<?php
}

function _gui_image2($name, $lbl, $value='', $type = false, $required=false, $error='', $info='', $props=array()) 
{
  $id = isset($props['id'])?$props['id']:$name;
  if($error!='') {
    ?>
<div class="error"><?php echo htmlspecialchars($error); ?></div>
<div class="row_error"><?php
} else {
  ?>
<div class="row"><?php
}
?>
<div class="row_left"><label for="<?php echo htmlspecialchars($id);?>"><?php echo ($required?'* ':'').htmlspecialchars($lbl); ?></label></div>
<div class="row_right"><input class="in" type="text"
	name="<?php echo htmlspecialchars($name); ?>"
	id="<?php echo htmlspecialchars($id); ?>"
	value="<?php echo htmlspecialchars($value); ?>"
	<?php
	if(is_array($props) && count($props)>0) {
	  foreach($props as $k=>$v) {
					if($k!='id') {
					  echo ' '.htmlspecialchars($k).'="'.htmlspecialchars($v).'"';
					}
	  }
	}
	?> /><? if($type) { ?><a
	href="#" onclick="load_u('list_gallery_images.php?field=<?php echo urlencode($id); ?>&type=<?php echo urlencode($type); ?>')"
	>Dodaj</a><? } ?> <?php 
	if($info!='') {
	  echo '<br /><span class="info">'.htmlspecialchars($info).'</span>';
	}
	?></div>
</div>
	<?php
}

function _gui_header($text) {
  echo '<h1>'.htmlspecialchars($text).'</h1>';
}

function _gui_form_start($name, $action='', $method='post', $visible=true, $enctype=false, $props=array()) 
{
  if ($visible) {
    echo '<div class="form_section">';
  }
  echo "\r\n";
  echo '<form id="'.htmlspecialchars($name).'" name="'.htmlspecialchars($name).'" action="'.htmlspecialchars($action).'" method="'.htmlspecialchars($method).'" ';
  if(is_array($props) && count($props)>0) {
    foreach($props as $k=>$v) {
      echo ' '.htmlspecialchars($k).'="'.htmlspecialchars($v).'"';
    }
  }
  if($enctype) {
    echo ' enctype="multipart/form-data"';
  }
  echo '>';
}

function _gui_form_end($visible=true) {
  echo '</form>';
  if($visible) {
    echo '</div>';
  }
}
function _gui_form_row($show_left=true) {
  echo '<div class="row">';
  if ($show_left) {
    echo '<div class="row_left">';
  }
}

function _gui_form_row2($show_left=true) {
  echo '<div class="row">';
  if($show_left) {
    echo '<div class="row_left2">';
  }
}

function _gui_form_row_mid($show_left=true) {
  if($show_left) {
    echo '</div>';
  }
  echo '<div class="row_right">';
}

function _gui_form_row_end($show_left=true) {
  if($show_left) {
    echo '</div>';
  }
  echo '</div>';
}
function _gui_break($t='') {
  ?>
<div class="row">
<h3><?php echo htmlspecialchars($t) ;?></h3>
</div>
  <?php
}

function _gui_block_start($id,$hidden=false,$style='') {
  ?>
<div id="<?php echo htmlspecialchars($id); ?>" style="display:<?php echo $hidden?'none':'block'; echo $style; ?>">
  <?php
}
function _gui_block_end() {
  ?></div>
  <?php
}

function _gui_checkbox($name, $lbl, $val=1, $checked=false, $error='', $info='', $props=array()) 
  {
  $id = isset($props['id']) ? $props['id'] : $name;
  if ($error!='') {
    ?>
<div class="error"><?php echo htmlspecialchars($error); ?></div>
<div class="row_error"><?php
} else {
  ?>
<div class="row"><?php
}
?>
<div class="row_left"><input class="in check" type="checkbox"
	name="<?php echo htmlspecialchars($name); ?>"
	id="<?php echo htmlspecialchars($id); ?>"
	value="<?php echo htmlspecialchars($val); ?>"
	<?php
	if($checked) {
	  echo ' checked ';
	}
	if(is_array($props) && count($props)>0) {
	  foreach($props as $k=>$v) {
					if($k!='id') {
					  echo ' '.htmlspecialchars($k).'="'.htmlspecialchars($v).'"';
					}
	  }
	}
	?> /></div>
<div class="row_right"><label for="<?php echo htmlspecialchars($id);?>"><?php echo htmlspecialchars($lbl); ?>
	<?php
	if($info!='') {
	  echo '<br /><span class="info">'.htmlspecialchars($info).'</span>';
	}
	?> </label></div>
</div>
	<?php
}

function _gui_hidden($name, $value='') 
  {
  echo '<input type="hidden" id="'.htmlspecialchars($name).'" name="'.htmlspecialchars($name).'" value="'.htmlspecialchars($value).'" />';
}

function _gui_text($name, $lbl, $value='', $maxlength=0, $required=false, $error='', $info='', $props=array()) 
{
  $id = isset($props['id']) ? $props['id'] : $name;
  if ($error != '') {
    ?>
<div class="error"><?php echo htmlspecialchars($error); ?></div>
<div class="row_error"><?php
} else {
  ?>
<div class="row"><?php
}
?>
<div class="row_left"><label for="<?php echo htmlspecialchars($id);?>"><?php echo ($required ? '* ' : '') . htmlspecialchars($lbl); ?></label></div>
<div class="row_right"><input class="in" type="text"
	name="<?php echo htmlspecialchars($name); ?>"
	id="<?php echo htmlspecialchars($id); ?>"
	value="<?php echo htmlspecialchars($value); ?>"
	<?php
	if (intval($maxlength) > 0) {
	  echo ' maxlength="' . intval($maxlength) . '" ';
	}
	if (is_array($props) && count($props) > 0) {
	  foreach ($props as $k => $v) {
					if ($k != 'id') {
					  echo ' ' . htmlspecialchars($k) . '="'.htmlspecialchars($v) . '"';
					}
	  }
	}
	?> /> <?php
	if ($info!='') {
	  echo '<br /><span class="info">' . htmlspecialchars($info) . '</span>';
	}
	?></div>
</div>
	<?php
}

function _gui_password($name,$lbl,$value='',$maxlength=0,$required=false,$error='',$info='',$props=array()) {
  $id = isset($props['id'])?$props['id']:$name;
  if($error!='') {
    ?>
<div class="error"><?php echo htmlspecialchars($error); ?></div>
<div class="row_error"><?php
} else {
  ?>
<div class="row"><?php
}
?>
<div class="row_left"><label for="<?php echo htmlspecialchars($id);?>"><?php echo ($required?'* ':'').htmlspecialchars($lbl); ?></label></div>
<div class="row_right"><input type="password" class="in"
	name="<?php echo htmlspecialchars($name); ?>"
	id="<?php echo htmlspecialchars($id); ?>"
	value="<?php echo htmlspecialchars($value); ?>"
	<?php
	if(intval($maxlength)>0) {
	  echo ' maxlength="'.intval($maxlength).'" ';
	}
	if(is_array($props) && count($props)>0) {
	  foreach($props as $k=>$v) {
					if($k!='id') {
					  echo ' '.htmlspecialchars($k).'="'.htmlspecialchars($v).'"';
					}
	  }
	}
	?> /> <?php
	if($info!='') {
	  echo '<br /><span class="info">'.htmlspecialchars($info).'</span>';
	}
	?></div>
</div>
	<?php

}

function _gui_button($value,$onclick='',$submit='',$props=array()) {
  if($submit!='') {
    $onclick = 'document.'.htmlspecialchars($submit).'.submit()';
  }
  /*
   ?>
   <div class="button <?php echo htmlspecialchars($class); ?>" onclick="<?php echo $onclick; ?>">
   <span class="left">&nbsp;&nbsp;</span><span class="mid" ><?php echo htmlspecialchars($value); ?></span><span class="row_right">&nbsp;&nbsp;</span>
   </div>
   <?php
   */
  ?> <input type="button" onclick="<?php echo $onclick; ?>"
	value="<?php echo htmlspecialchars($value); ?>" class="btn" /> <?php
}

function _gui_textarea($name, $lbl, $value='', $cols=30, $rows=5, $wysiwyg=0, $required=false, $error='', $info='', $props=array()) 
  {
  $id = isset($props['id']) ? $props['id'] : $name;
  if ($error!='') {
    ?>
<div class="error"><?php echo htmlspecialchars($error); ?></div>
<div class="row_error"><?php
} else {
  ?>
<div class="row"><?php
}
if ($wysiwyg != WYSIWYG_FULL) {
  ?>
<div class="row_left"><label for="<?php echo htmlspecialchars($id);?>"><?php echo ($required ? '* ' : '').htmlspecialchars($lbl); ?></label></div>
<div class="row_right"><?php
} else {
  ?>
<div
	style="text-alignment: center; margin: 0px 0px 0px 0px; padding: 0px">
<label for="<?php echo htmlspecialchars($id);?>"><?php echo ($required ? '* ' : '').htmlspecialchars($lbl); ?></label><br />
  <?php
}
?> <textarea name="<?php echo htmlspecialchars($name); ?>" class="in"
	id="<?php echo htmlspecialchars($id); ?>"
	rows="<?php echo intval($rows); ?>" cols="<?php echo intval($cols); ?>"
	<?php
	if (is_array($props) && count($props) > 0) {
			foreach($props as $k=>$v) {
			  if($k!='id') {
			    echo ' '.htmlspecialchars($k).'="'.htmlspecialchars($v).'"';
			  }
			}
	}
	?>><?php echo htmlspecialchars($value); ?></textarea> <?php
	if ($info!='') {
			echo '<br /><span class="info">'.htmlspecialchars($info).'</span>';
	}
	?></div>
</div>
	<?php
	switch($wysiwyg) {
	  default:
	  case WYSIWYG_NONE:
	    break;
	    
	  case WYSIWYG_SIMPLE:
	    ?> <script type="text/javascript">
			tinyMCE.init({
				mode : "exact",
				elements : "<?php echo addslashes($id); ?>",
				language : "en",
				theme : "advanced",
				theme_advanced_buttons1 : "bold,italic,underline,separator,bullist,numlist,undo,redo,link,unlink,separator,code",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
			});
			</script> <?php
			break;
case WYSIWYG_FULL:
  ?> <script type="text/javascript">
			tinyMCE.init({
				mode : "exact",
				elements : "<?php echo addslashes($id); ?>",
				language : "en",
				theme : "advanced",
				/*
				
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
				*/
				plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,pagebreak",//imagemanager,filemanager",
				theme_advanced_buttons1_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
				//theme_advanced_buttons2_add_before : "bold,italic,underline,separator,bullist,numlist,undo,redo,link,unlink",
				theme_advanced_buttons3_add_before : "tablecontrols,separator",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_buttons2_add : "media"
				//theme_advanced_buttons3_add : "forecolor,backcolor,fontselect,fontsizeselect"
			});
			</script> <?php
			break;
}
}

function _gui_select($name, $lbl, $value='', $data=array(), $val_func='', $txt_func='', $required=false, $error='', $info='', $props=array()) 
{
  $id = isset($props['id'])?$props['id']:$name;
  if ($error!='') {
    ?>
<div class="error"><?php echo htmlspecialchars($error); ?></div>
<div class="row_error"><?php
} else {
  ?>
<div class="row"><?php
}
?>
<div class="row_left"><label for="<?php echo htmlspecialchars($id);?>"><?php echo ($required?'* ':'').htmlspecialchars($lbl); ?></label></div>
<div class="row_right"><?php
_gui_select_field($name,$id,$value,$data,$val_func,$txt_func,$props);
if($info!='') {
  echo '<br /><span class="info">'.htmlspecialchars($info).'</span>';
}
?></div>
</div>
<?php
}
function _gui_select_field($name,$id,$value='',$data=array(),$val_func='',$txt_func='',$props=array()) {
  ?> <select name="<?php echo htmlspecialchars($name); ?>" class="in"
	id="<?php echo htmlspecialchars($id); ?>"
	<?php
	if(is_array($props) && count($props)>0) {
	  foreach($props as $k=>$v) {
	    if($k!='id') {
	      echo ' '.htmlspecialchars($k).'="'.htmlspecialchars($v).'"';
	    }
	  }
	}
	?>>
	<?php
	$use_val_function = ($val_func!='' && function_exists($val_func));
	$use_txt_function = ($txt_func!='' && function_exists($txt_func));
	foreach($data as $k=>$v) {
			$val = $use_val_function?$val_func($k,$v):$k;
			$txt = $use_txt_function?$txt_func($k,$v):$v;
			echo '<option value="'.htmlspecialchars($val).'"';
			if($val==$value) {
			  echo ' selected ';
			}
			echo '>'.htmlspecialchars($txt).'</option>';
	}
	echo '</select>';
}

function _gui_stats($tab) {
  ?>
	<div class="stats"><?php
	foreach($tab as $k=>$v) {
			echo htmlspecialchars($k).' '.htmlspecialchars($v).'<br />';
	}
	?></div>
	<?php
}

function _gui_date($name,$lbl,$value,$time=false,$required=false,$error='',$info='',$props=array()) {
  $id = isset($props['id'])?$props['id']:$name;
  if($error!='') {
    ?>
	<div class="error"><?php echo htmlspecialchars($error); ?></div>
	<div class="row_error"><?php
} else {
  ?>
	<div class="row"><?php
}
?>
	<div class="row_left"><label for="<?php echo htmlspecialchars($id);?>"><?php echo ($required?'* ':'').htmlspecialchars($lbl); ?></label></div>
	<div class="row_right"><?php
	_gui_datefield($name,$id,$value,$time,$props);
	if($info!='') {
	  echo '<br /><span class="info">'.htmlspecialchars($info).'</span>';
	}
	?></div>
	</div>
	<?php
}

function _gui_datefield($name,$id,$value,$time=false,$props=array()) {
  ?> <input class="in datefield" type="text"
		name="<?php echo htmlspecialchars($name); ?>"
		id="<?php echo htmlspecialchars($id); ?>"
		value="<?php echo htmlspecialchars($value); ?>"
		<?php
		echo ' maxlength="'.($time?16:10).'" ';
		if(is_array($props) && count($props)>0) {
		  foreach($props as $k=>$v) {
		    if($k!='id') {
		      echo ' '.htmlspecialchars($k).'="'.htmlspecialchars($v).'"';
		    }
		  }
		}
		?> /><input type="image" src="<?php echo ADMIN_PATH; ?>img/date.gif"
		id="<?php echo $id.'_img'; ?>" class="dateimg" /> <script
		type="text/javascript">
		Calendar.setup({ 'inputField':'<?php echo $id; ?>','ifFormat': '%Y-%m-%d<?php echo $time?' %H:%M':''; ?>', 'showsTime':<?php echo $time?'true':'false'; ?>, 'timeFormat':24, 'button':'<?php echo $id; ?>_img','weekNumbers':false});
	</script> <?php
}

/**
 * Generuje link.
 * 
 * @param $linkLabel Tekst wyświetlany w linku
 * @param $href Adres przekierowania
 * @param $target Typ przekierowania: _self,_blank
 * @return unknown_type
 */
function _gui_link($linkLabel,$href,$target) {
	?>
	<div>
	<div class="row_left"></div>
    <div class="row_right">
        <a href="<?php echo $href ?>" target="<?php echo $target?>" ><?php echo $linkLabel?></a>
    </div>
    <?php
}
