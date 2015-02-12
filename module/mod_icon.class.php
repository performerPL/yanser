<?php

define('mod_icon.class', 1);

class mod_icon
{
	function update($tab)
	{
		return _db_replace('mod_icon',
		  array(
		  'module_id'=>_db_int($tab['module_id']),
		  'icon_path'=>_db_string($tab['icon_path']),
		  'icon_description'=>_db_string($tab['icon_description']),
		  'show_enlarge' => _db_int($tab['show_enlarge']),
		  )
		);
	}

	function remove($id)
	{
		return _db_delete('mod_icon', 'module_id='.intval($id),1);
	}

	function validate($tab, $T)
	{
		return true;
	}

	function get($id)
	{
		return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_icon` WHERE module_id='.intval($id).' LIMIT 1');
	}
	//function front($module,$Item) {
	//	echo '<img src="'.$Item->getIcon().'" alt="" />';
	//}

	function front($module, $Item)
	{
		global $GL_CONF;
		$cfg = $GL_CONF['IMAGES_FILES'];

		$data = $this->get($module['module_id']);
		$style = $module['module_style'];
		$styles = array(
		0	=> ' class="mod_icon mod_icon_0" ',
		1	=> ' class="mod_icon mod_icon_1" ',
		2	=> ' class="mod_icon mod_icon_2" ',
		3	=> ' class="mod_icon mod_icon_3" ',
		4	=> ' class="mod_icon mod_icon_4" ',,
		5	=> ' class="mod_icon mod_icon_5" ',
		6	=> ' class="mod_icon mod_icon_6" ',
		7	=> ' class="mod_icon mod_icon_7" ',
		8	=> ' class="mod_icon mod_icon_8" ',
		9	=> ' class="mod_icon mod_icon_9" '
		);
		
		if (!$data['icon_description']) {
			$alt = ALT_TEXT;
			$title = TITLE_TEXT;
		} else {
			$alt = $data['icon_description'];
			$alt = strip_tags($alt);
			$title = $alt;
		}
		
		$imgHtml = '<img  src="' . $cfg['IMAGE_BASE_URL'] . $Item->getIcon() . '"  alt="'.$alt.'" />';

		echo '<div   ' . $styles[$style] . ' id="mod_icon_'.$data['module_id'] .'">';
		
		// gdy podana scieżka do przekierowania
			if(!empty($data[icon_target_url])) {
				echo '<a href="'. $data[image_target_url] .'" target="_'. $data[icon_target] .'"  title="'.$title.'">';
				echo $imgHtml;
				echo '</a>';
			}

		// gdy zaznaczone pokazywanie powiększenia
			else if(!empty($data[show_enlarge])) {
						$fileName = explode("/",$Item->getIcon());
            $fileName = $fileName[count($fileName)-1];
						
						echo '<script type="text/javascript">';
						echo '$(function() {';
						echo '	$(\'#mod_icon_'.$data['module_id'].' a\').lightBox();';
						echo '});';
						echo '</script>';
						
						echo '<a href="'.htmlspecialchars($cfg["IMAGE_BASE_URL"] . $cfg['IMAGE_DIR_3'] . $fileName).'"  title="'.$title.'" >';			
						echo $imgHtml;
            echo '</a>';
		}
		else {
			echo $imgHtml;
		}
		echo '</div>';
	}

}
