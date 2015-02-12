<?php
//if(defined('mod_text.class')) die('aa');
define('mod_image.class', 1);

class mod_image
{
	function update($tab)
	{
		global $GL_CONF;
		$cfg = $GL_CONF['IMAGES_FILES'];
		$A = array(htmlspecialchars($cfg["IMAGE_DIR_1"]), htmlspecialchars($cfg["IMAGE_DIR_2"]), htmlspecialchars($cfg["IMAGE_DIR_3"]), htmlspecialchars('gallery/orig/'));
		$tab['image_path'] = str_replace($A, $tab['image_type'], $tab['image_path']);
		return _db_replace('mod_image', 
		  array(
		  'module_id'=>_db_int($tab['module_id']),
		  'image_path'=>_db_string($tab['image_path']),
		  'image_type' => _db_string($tab['image_type']),
		  'image_description'=>_db_string($tab['image_description']),
		  'show_enlarge' => _db_int($tab['show_enlarge']),
		  )
		);
	}

	function remove($id)
	{
		return _db_delete('mod_image', 'module_id=' . intval($id), 1);
	}

	function validate($tab, $T)
	{
		return true;
	}

	function get($id)
	{
		return _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_image` WHERE module_id=' . intval($id) . ' LIMIT 1');
	}

	function front($module, $Item)
	{
		global $GL_CONF;
		$cfg = $GL_CONF['IMAGES_FILES'];
		$data = $this->get($module['module_id']);
		$style = $module['module_style'];
		$styles = array(
		0	=> ' mod_image mod_image_0 ',
		1	=> ' mod_image mod_image_1 box box-3-4 ',
		2	=> ' mod_image mod_image_2 box box-2-3 ',
		3	=> ' mod_image mod_image_3 box box-1-2 ',
		4	=> ' mod_image mod_image_4 box box-1-3 ',
		5	=> ' mod_image mod_image_5 box box-1-4 ',
		6	=> ' mod_image mod_image_6 box box-3-4 ',
		7	=> ' mod_image mod_image_7 box box-2-3 ',
		8	=> ' mod_image mod_image_8 box box-1-2 ',
		9	=> ' mod_image mod_image_9 box box-1-3 ',
		10	=> ' mod_image mod_image_10 box box-1-4 '
		);
		if (!$data['image_description']) {
			$alt = ALT_TEXT;
			$title = TITLE_TEXT;
		} else {
			$alt = $data['image_description'];
			$alt = strip_tags($alt);
			$title = $alt;
		}

		$imgHtml = '<img  src="' . htmlspecialchars($cfg['IMAGE_BASE_URL'] . $data['image_path']) . '"  alt="'.$alt.'" />';
      
      if ($module['module_style'] == 0) {
         echo '<div class="width_site width_'.$data['module_id'].'"><div class="inside_content">';
      }
      
		
		echo '<div   class="' . $styles[$style] . 'mod_'.$data['module_id'] .'" id="mod_'.$data['module_id'] .'" ><div class="margin">';
		
		
		// gdy podana sciezka do przekierowania
			if(!empty($data[image_target_url])) {
				//echo '<a  href="'. $data[image_target_url] .'" target="_'. $data[image_target] .'"  title="'.$title.'">';
				echo $imgHtml;
				//echo '</a>';
			}
		
		
	    // gdy zaznaczone pokazywanie powiekszenia
        else if(!empty($data[show_enlarge])) {
            $fileName = explode("/",$data['image_path']);
            $fileName = $fileName[count($fileName)-1];
			
						echo '<a href="'.htmlspecialchars($cfg["IMAGE_BASE_URL"] . $cfg['IMAGE_DIR_3'] . $fileName).'"  title="'.$title.'"  class="zoom mod_'.$data['module_id'] .'"><div class="inside">';
                  echo $imgHtml;
						echo $data['image_description'];
						echo '</div></a>';
						
						echo '<script type="text/javascript">';
						echo '$(function() {';
						echo '	$(\'.mod_'.$data['module_id'].' a\').lightBox();';
						echo '});';
						echo '</script>';
        }
		else {
			echo $imgHtml;
		}
		echo '</div></div>';
		if ($module['module_style'] == 0) {
		    echo '</div></div>';
		}
      
	}
}


