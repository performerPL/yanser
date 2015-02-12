<?php

define('mod_icon.class', 1);

class mod_icon
{
	function update($tab)
	{
		$updateArray = array(
  		'module_id'=>_db_int($tab['module_id']),
  		'icon_path'=>_db_string($tab['icon_path']),
  		'icon_description'=>_db_string($tab['icon_description']),
  		'icon_target_url'=>_db_string($tab['icon_target_url']),
		'icon_target'=>_db_string($tab['icon_target']),
  	    'show_enlarge' => _db_int($tab['show_enlarge']),
		);
		return _db_replace('mod_icon',$updateArray);
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
		$cfg = $GL_CONF["IMAGES_FILES"];

		$data = $this->get($module['module_id']);
		$style = $module['module_style'];
		$styles = array(
		0	=> 'style="float: left; margin: 0 10px 10px 0;" ',
		1	=> 'style="float: right; margin: 0 0 10px 10px;" ',
		2	=> 'style="margin: 20px 0; text-align: left" '
		);
		echo '<div ' . $styles[$style] . '>';
		echo '<img src="' . $cfg['IMAGE_BASE_URL'] . $Item->getIcon() . '" /><br>' . $data['icon_description'];
		echo '</div>';
	}

}
