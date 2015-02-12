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

		$updateArray = array(
			'module_id'=>_db_int($tab['module_id']),
			'image_path'=>_db_string($tab['image_path']),
			'image_type' => _db_string($tab['image_type']),
			'image_description'=>_db_string($tab['image_description']),
			'image_target_url'=>_db_string($tab['image_target_url']),
			'image_target'=>_db_string($tab['image_target']),
		    'show_enlarge' => _db_int($tab['show_enlarge']),
		);
		return _db_replace('mod_image', $updateArray);
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
		0	=> 'style="float: left; margin: 0 10px 10px 0;" ',
		1	=> 'style="float: right; margin: 0 0 10px 10px;" ',
		2	=> 'style="margin: 20px 0; text-align: left" '
		);

		echo '<div ' . $styles[$style] . '>';
		echo '<img src="' . htmlspecialchars($cfg['IMAGE_BASE_URL'] . $data['image_path']) . '" />';
		echo '<br/>';
		echo $data['image_description'];
		echo '</div>';

	}
}
