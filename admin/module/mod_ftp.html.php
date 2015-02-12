<?php 
function array_search_all($needle, $haystack)
{#array_search_match($needle, $haystack) returns all the keys of the values that match $needle in $haystack
if (!is_array($haystack)) {
  $haystack = array();
}
    foreach ($haystack as $k=>$v) {
    
        if($haystack[$k]==$needle){
        
           $array[] = $k;
        }
    }
    return ($array);

    
}
$FILES = _ftp_get($Tab['module_id']);
?>

<div class="row">
			<div class="row_left">
					<input id="show_description" class="in check" type="checkbox" value="1" name="show_description" <?php if ($FILES[0]['show_description'][0]> 0): ?>checked="checked"<?php endif ?>/>
			</div>
			<div class="row_right"><label for="show_descr">pokaz opis </label></div>
</div>

<?php
$groups = group_list();
$files = get_all_files();
$galleries = array('' => 'nie wybrano');
$FILES = _ftp_get($Tab['module_id']);
$kk = 0;
$pga = array();
foreach ($FILES as $item) {
  if ($item['item_type'] == 'f') {
    $files_used[$kk] = $item['item_id'];
    $pga[$kk] = $item['group_id'];
    $kk++;
  } elseif ($item['item_type'] == 'g') {
    $groups_used[] = $item['item_id'];
  }
}
?>
<div class="row">
			<div class="row_left"><?php _t('mod_ftp_select_groups'); ?></div>
</div>
<?php
if (count($groups) > 0) {
  foreach ($groups as $group) {
    $props = array('onclick'=>"getFilesFromGroup(".$group['group_id'].")");
    $group_files = group_files($group['group_id']);
   
    //<div class="row">
    //<div class="row_left">
					
					$propy = array('id' => 'grupka_' . $group['group_id']);
					$is_in_group = checkFileinGroup($group['group_id']);
					$dis = false;
					if (count($is_in_group) > 0) {
						$dis = true;
					}
					$ch = is_array($groups_used) && count($is_in_group) == 0 ? is_numeric(array_search($group['group_id'], $groups_used)) ? true : false : false;
					?>
					<div class="row">
								<div class="row_left">
								
											<input id="grupka_<?php echo $group['group_id'] ?>" class="in check" type="checkbox" value="<?php echo $group['group_id'] ?>" name="group_id[]" <?php if ($ch): ?>checked="checked"<?php endif ?><?php if ($dis == true): ?> disabled="disabled"<?php endif ?>/>
								</div>
								<div class="row_right">
								
								<label for="show_descr"><?php echo $group['group_name'] ?></label>&nbsp;&nbsp;&nbsp;
								<!-- WYBRANE PLIKI Z GRUPY - wylaczone bo nie dziala prawidlowo
								<span style="color: #aaa">(
								<input id="group_id_files[]" onclick="getFilesFromGroup(<?php echo $group['group_id'] ?>); odznacz_i(this, <?php echo $group['group_id'] ?>);" class="in check" type="checkbox" value="<?php echo $group['group_id'] ?>" name="group_id_files[]" <?php if (count($is_in_group) > 0): ?>checked="checked"<?php endif ?>/>
								<label for="x">Wybrane pliki</label>
								)</span>-->
								</div>
					<!--</div>-->

					<!--<div class="row">
								<div class="row_left">
											
								</div>
								<div class="row_right"></div>-->
					</div>
					<div id="<?php echo htmlspecialchars('group_files__' . $group['group_id']); ?>" style="margin-left: 20px; display:<?php if (count($is_in_group) > 0) echo 'block'; else echo 'none'; ?>">
					<?php
					if (count($group_files) > 0) {
						foreach ($group_files as $file) {
						  $searched = false;
						  
						  
						  
						    $tede = array_search_all($file['file_id'], $files_used);
						    if (!is_array($tede)) {
						      $tede = array();
						    }
						    foreach ($tede as $vv) {
						      if ($pga[$vv]['group_id'] == $group['group_id']) {
						       $searched = true; 
						      }
						    }
						  
							_gui_checkbox('file_id[]',$file['file_title'], $file['file_id'] . '-' . $group['group_id'], ($file['group_id'] == $group['group_id'] && is_array($files_used) && is_numeric(array_search($file['file_id'], $files_used)) && $searched) ? true : false, '', '', array('rel' => $group['group_id']));
						} 
					}
					?>
					</div>
					<?php
    //</div>
    //</div>
    
  }
}
