<?php 
if (!defined('_APP')) {
  exit; 
}
?>
<script type="text/javascript">
function load_u(url)
{
  document.getElementById('gal_add').style.display = 'block';
  document.getElementById('gal_add2').style.display = 'block';
  var x = new Ajax.Updater("gal_add2", url, {
      method: "get",
      onComplete: function() {}
  });
}

function unload_u()
{
  document.getElementById('gal_add').style.display = 'none';
  document.getElementById('gal_add2').style.display = 'none';
}
</script>

	<?php
	_gui_form_row(false);
	?>
	<div class="box"></div>
	<div class="box"></div>
	<?php
	if (empty($_GET['copy'])) {
	foreach ($GL_ITEM_TYPE as $type => $v) {
		if( (ITEM_COPY == $type) && (!isset($isAdd)) ) 
		      continue;
		?>
		<div class="box">
			<label for="item_type_<?php echo intval($type); ?>">
			<img src="<?php echo ADMIN_PATH . htmlspecialchars($v['icon']); ?>" alt="<?php _t($v['name']); ?>" border="0" width="" /><br />
			<input type="radio" name="item_type" id="item_type_<?php echo intval($type); ?>" value="<?php echo intval($type); ?>" <?php echo $Tab['item_type']==$type?' checked ':'' ?>  onclick="item_typeClick(<?php echo intval($type); ?>)" />&nbsp;<?php echo _t($v['name']); ?>
			</label>
		</div>
		<?php
	}
}
	_gui_form_row_end(false);
//	if (!empty($_GET['copy'])) {
	?> 
<div id="copy_div" style="display: none;">
  <div class="row">
      <div class="row_left">
        <label for="parent_id">* Kopiuj z:</label>
      </div>
        <div class="row_right">
          <select id="mirror_id" class="in" name="mirror_id">
          <option <?php if ($Tab['mirror_id'] == 0): ?>selected="selected" <?php endif ?>value="0"> ----------------- </option>
          <?php _gui_select_item_print($Items[0]['subitems'], 0, $Tab['mirror_id'], $Items, $Tab['item_id']); ?>
          </select><br/>
           <input type="text" name="mirror_id_a" value="<?php echo $Tab['mirror_id_a'] ?>" class="in"/>
        </div>
  </div>
</div>  
	<?php
//}
	_gui_select('menu_id',$T['item_menu_id'],$Tab['menu_id'],$Menus,'','menu_id_txt_func',true,$Error['menu_id'],$T['item_menu_id_info'],array('onchange'=>'menuChanged(this, ' . ($Tab['parent_id'] ? $Tab['parent_id'] : 0) . ',' . $Tab['item_id'] . ')'));
    ?>
	<div id="parent_id_div">
	<div class="row">
    	<div class="row_left">
    		<label for="parent_id">* <?php echo $T['item_parent_id'] ?></label>
    	</div>
        <div class="row_right">
          <select id="parent_id" class="in" name="parent_id">
          <?php if (isset($_GET['as'])): ?>
          <?php $Tab['parent_id'] = intval($_GET['as']); ?>
          <?php endif ?>
          <option <?php if ($Tab['parent_id'] == 0): ?>selected="selected" <?php endif ?>value="0"> ----------------- </option>
          <?php _gui_select_item_print($Items[0]['subitems'], 0, $Tab['parent_id'], $Items, $Tab['item_id']); ?>
          </select>
          <input type="button" value="Przejdź do" onclick="document.location='<?php echo ADMIN_PATH ?>edit_item.php?item_id=' + document.getElementById('parent_id').options[document.getElementById('parent_id').selectedIndex].value; return false;" /> 
        </div>
	</div>

</div>

<div id="parent_id_div3">
    <div class="row">
        <div class="row_left">
            <label for="parent_id3"> <?php echo $T['item_parent_id3'] ?></label>
        </div>
        <div class="row_right">
          <select id="parent_id3" class="in" name="parent_id3">
          <option <?php if ($Tab['parent_id3'] == 0): ?>selected="selected" <?php endif ?>value="0"> ----------------- </option>
          <?php _gui_select_item_print($Items[$Tab['parent_id']]['subitems'], 1, 0, $Items, $Tab['item_id']); ?>
          </select>
          <input type="button" value="Przejdź do" onclick="document.location='<?php echo ADMIN_PATH ?>edit_item.php?item_id=' + document.getElementById('parent_id3').options[document.getElementById('parent_id3').selectedIndex].value; return false;" /> 
          <?php if ($Tab['item_id'] > 0): ?>
          <input type="button" value="Dodaj nową" onclick="document.location='<?php echo ADMIN_PATH ?>add_item.php?item_id=0&menu_id=<?php echo $Tab['menu_id'] ?>&as=<?php echo $Tab['parent_id'];// zmienna odpowiada za nadpisanie parent_id przy dodawaniu ?>'; return false;" />
          <?php endif ?>
        </div>
    </div>

</div>

<div id="parent_id_div2">
	<div class="row">
    	<div class="row_left">
    		<label for="parent_id2"> <?php echo $T['item_parent_id2'] ?></label>
    	</div>
        <div class="row_right">
          <select id="parent_id2" class="in" name="parent_id2">
          <option <?php if ($Tab['parent_id2'] == 0): ?>selected="selected" <?php endif ?>value="0"> ----------------- </option>
          <?php _gui_select_item_print($Items[$Tab['item_id']]['subitems'], 1, $Tab['parent_id'], $Items, $Tab['item_id'], true); ?>
          </select>
          <input type="button" value="Przejdź do" onclick="document.location='<?php echo ADMIN_PATH ?>edit_item.php?item_id=' + document.getElementById('parent_id2').options[document.getElementById('parent_id2').selectedIndex].value; return false;" /> 
          <?php if ($Tab['item_id'] > 0): ?>
          <input type="button" value="Dodaj nową" onclick="document.location='<?php echo ADMIN_PATH ?>add_item.php?item_id=-1&menu_id=<?php echo $Tab['menu_id'] ?>&as=<?php echo $Tab['item_id'] ?>'; return false;" />
          <?php endif ?>
        </div>
	</div>

</div>
<?php
	_gui_text('item_name',$T['item_name'],$Tab['item_name'],120,true,$Error['item_name'],$T['item_name_info']);
	_gui_text('item_long_name',$T['item_long_name'],$Tab['item_long_name'],255,false,'',$T['item_long_name_info']);
	_gui_text('item_code',$T['item_code'],$Tab['item_code'],255,false,$Error['item_code'],$T['item_code_info']);
	_gui_checkbox('page_start',$T['item_page_start'],1,$Tab['page_start']>0,'',$T['item_show_in_map_info']);
	_gui_textarea('item_description', $T['item_description'], $Tab['item_description'], 30, 5, WYSIWYG_SIMPLE, false, '', $T['item_description_info']);
	_gui_text('item_meta_title', $T['item_meta_title'],$Tab['item_meta_title'],255,false,$Error['item_meta_title'],$T['item_meta_title']);
	_gui_image2('item_icon',$T['item_icon'],$Tab['item_icon'],FILE_ICON,false,$Error['item_icon'],$T['item_icon_info']);
	

	if(!empty($Tab['item_icon'])) { 
?>
    <div class="row">
        <div class="row_left">
          
        </div>
        <div class="row_right">
        <img src="../<?php echo $Tab['item_icon'];?>" height="100" >
        </div> 
    </div>
    
<?php } ?>
<div id="gal_add" class="row" style="display: none">
  <div id="gal_add2" style="display: none"></div>
</div>
<div class="row">
  <div class="row_left">
  	<label for="item_description">utworzono:</label>
  </div>
<div class="row_right">
<?php echo $Tab['created'] . ' - ' . $Tab['created_by_name'] ?>
</div>
</div>

<div class="row">
  <div class="row_left">
    <label for="item_description">zmodyfikowano:</label>
  </div>
<div class="row_right">
<?php if (!empty($Tab['modificated']) && !is_null($Tab['modificated'])): ?>
<?php echo $Tab['modificated'] . ' - ' . $Tab['modificated_by_name'] ?>
<?php else: ?>
brak danych
<?php endif ?>
</div>
</div>

<div class="row">
  <div class="row_left">
  	<label for="item_counter">licznik odwiedziń:</label>
  </div>
  <div class="row_right">
  <?php echo $Tab['article']['counter'] ?>
  </div>
</div>
	<?php
	_gui_block_start('target_id_div', ($Tab['item_type'] != ITEM_LINK_IN && $Tab['item_type'] != ITEM_MIRROR));
		//_gui_hidden('target_id',0);
		_gui_select_item_all('target_id',$T['item_target_id'],$Tab['target_id'],$Tab['item_id'], false,true,$Error['target_id'],$T['item_target_id_info']);
	_gui_block_end();
	_gui_block_start('link_url_div',($Tab['item_type']!=ITEM_LINK_OUT));
		_gui_text('link_url',$T['item_link_url'],$Tab['link_url'],255,true,$Error['link_url'],$T['item_link_url_info']); //widoczne jesli link out
	_gui_block_end();
	_gui_block_start('link_target_div',($Tab['item_type']!=ITEM_LINK_IN && $Tab['item_type']!=ITEM_LINK_OUT));
		_gui_select('link_target',$T['item_link_target'],$Tab['link_target'],$GL_LINK_TARGET,'','translation_txt_func',true,$Error['link_target'],$T['item_link_target_info']); //widoczne jeśli link in lub link out
	_gui_block_end();
		
	function translation_txt_func($k, $v) 
	{
	  global $T;
	  return htmlspecialchars($T[$v]);
	}
	
	function menu_id_txt_func($k, $v) 
	{
	  //global $Config;
	  return htmlspecialchars($v['lang_id'] . ': ' . $v['menu_name']);
	}
	?>
	<script type="text/javascript">
	
	function item_typeClick(x) 
	{
		var  link_url = document.getElementById('link_url_div');
		var  link_tgt = document.getElementById('link_target_div');
		var  tgt_id = document.getElementById('target_id_div');
		var  copy_div = document.getElementById('copy_div');
		switch(x) {
			case <?php echo intval(ITEM_MIRROR); ?>:
				link_url.style.display = 'none';
				link_tgt.style.display = 'none';
				tgt_id.style.display = 'block';
				copy_div.style.display = 'none';
				break;
				
			case <?php echo intval(ITEM_ARTICLE); ?>:
				link_url.style.display = 'none';
				link_tgt.style.display = 'none';
				tgt_id.style.display = 'none';
				copy_div.style.display = 'none';
				break;
				
			case <?php echo intval(ITEM_LINK_IN); ?>:
				link_url.style.display = 'none';
				link_tgt.style.display = 'block';
				tgt_id.style.display = 'block';
				copy_div.style.display = 'none';
				
				/*var link_target = document.getElementById('link_target');
				
				//if(link_target.value==<?php echo intval(LINK_TARGET_INCLUDE); ?> || link_target.value==<?php echo intval(LINK_TARGET_IFRAME); ?>) {
					link_target.options[0].selected=true;
					
				}
				//link_target.options[<?php echo intval(LINK_TARGET_IFRAME); ?>].disabled= true;
				//link_target.options[<?php echo intval(LINK_TARGET_INCLUDE); ?>].disabled= true;
				*/
				break;
				
			case <?php echo intval(ITEM_LINK_OUT); ?>:
				link_url.style.display = 'block';
				link_tgt.style.display = 'block';
				tgt_id.style.display = 'none';
				copy_div.style.display = 'none';
				/*var link_target = document.getElementById('link_target');
				//link_target.options[<?php echo intval(LINK_TARGET_IFRAME); ?>].disabled= false;
				//link_target.options[<?php echo intval(LINK_TARGET_INCLUDE); ?>].disabled= false;
				*/
				break;

            case <?php echo intval(ITEM_COPY); ?>:
                link_url.style.display = 'none';
                link_tgt.style.display = 'none';
                tgt_id.style.display = 'block';
                copy_div.style.display = 'block';
                break;
		}
	}
	</script>