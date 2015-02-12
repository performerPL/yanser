<?php

define('mod_ftp.class', 1);

ini_set('register_globals', false);
set_magic_quotes_runtime(false);
class mod_ftp
{

  function update($tab)
  {
    $this->remove($tab['module_id']);
    _db_query("DELETE FROM " . DB_PREFIX . "mod_ftp WHERE module_id = " . _db_int($tab['module_id']) . " AND item_type='g'");
    if (is_array($tab['group_id'])) {
      foreach ($tab['group_id'] as $group_id) {
        $t = array(
					'module_id'=>_db_int($tab['module_id']),
					'item_id' => _db_int($group_id),			
					'item_type' => _db_string('g'),
          'show_description' => _db_int($tab['show_description'])
        );
        //print_r($t);
        _db_insert('mod_ftp', $t);
        //	return false;
      }
    }
    
    _db_query("DELETE FROM " . DB_PREFIX . "mod_ftp WHERE module_id = " . _db_int($tab['module_id']) . " AND item_type='f'");
    
    if (is_array($tab['file_id'])) {
      foreach ($tab['group_id_files'] as $group) {
        foreach ($tab['file_id'] as $file_id) {
          $gi = 0;
          if (strpos($file_id, '-') > 0) {
            $X = explode('-', $file_id);
            $file_id = $X[0];
            $gi = $X[1];
          }
          $t = array(
						'module_id'=>_db_int($tab['module_id']),
						'item_id' => _db_int($file_id),
						'item_type' => _db_string('f'),
            'group_id' => _db_int($gi),
            'show_description' => _db_int($tab['show_description'])			
          );
          //print_r($t);
          _db_insert('mod_ftp', $t);
        }
      }
    }
    
    //TODO dokonczyc update/insert, gdyz to nie to samo co w reszcie
    
    return true;
  }

  function remove($id)
  {
    return _db_delete('mod_ftp', 'module_id=' . intval($id));
  }

  function validate($tab, $T)
  {
    return $tab['gallery_id'] > 0;
  }

  function get($id)
  {
    $sql = 'SELECT * FROM `' . DB_PREFIX . 'mod_ftp` WHERE module_id='.intval($id);
    $res = array();
    $res = _db_get_one($sql);
    return res;
  }

  function front($module, $Item)
  {
    $ftp = new File_Manager_System('../');
    $data_groups = $this->get($module['module_id'], 'g');
    $files = $this->get($module['module_id'], 'f');
    $style = $module['module_style'];

    foreach ($data_groups as $data_group) {
      $group[] = group_files($data_group['item_id']);
    }

    if ($module['show_module_title']) {
      echo '<div class="mod_ftp_name">' . $module['module_name'] . '</div>';
    }
    if (count($group) > 0) {
      global $GL_CONF;
      $cfg = $GL_CONF['IMAGES_FILES'];
      switch ($style) {
        case 0:	// lista
          echo '<ul class="index_files">';
          foreach ($group as $gr) {
            foreach ($gr as $file) {
              echo '<li>';
              echo '<a href="'.$ftp->mainDirectory.$file['file_path'].'/'.$file['file_name'].'">'.$file['file_title'].'</a>';
              echo '<div class="space"></div></li>';
            }
          }
          echo '</ul>';
          break;
      }
    }
    if (count($files) > 0) {
      switch ($style)	{
        case 0:	// lista
          echo '<ul class="index_files">';
          foreach ($files as $file) {
            $file_info = get_file_info($file['item_id']);
            $file_title = $file_info[0]['file_title']==''?$file_info[0]['file_name']:$file_info[0]['file_title'];
            echo '<li>';
            echo '<a href="'.$ftp->mainDirectory.$file_info[0]['file_path'].'/'.$file_info[0]['file_name'].'">'.$file_title.'</a>';
            echo '<div class="space"></div></li>';
          }
          echo '</ul>';
          break;
      }
    }
  }

}
