<?php
//if(defined('mod_text.class')) die('aa');
define('mod_ftp.class', 1);
//require_once('../lib/ftp.php');

class mod_ftp
{

  function update($tab)
  {
    $t = array(
      'module_id' => $tab['module_id'],
      'show_descr' => _db_int($tab['show_descr'])
    );
    
    _db_replace('mod_ftp_options', $t);
    
    $this->remove($tab['module_id']);
    if (is_array($tab['group_id'])) {
      foreach ($tab['group_id'] as $group_id) {
        $t = array(
					'module_id'=>_db_int($tab['module_id']),
					'item_id' => _db_int($group_id),			
					'item_type' => _db_string('g')
        );
        //print_r($t);
        _db_insert('mod_ftp', $t);
        //	return false;
      }
    }
    
    _db_query("DELETE FROM " . DATABASE_PREFIX . "mod_ftp WHERE module_id = " . _db_int($tab['module_id']) . " AND item_type='f'");
    
    if (is_array($tab['file_id'])) {
      foreach ($tab['group_id_files'] as $group) {
      //  foreach ($tab['file_id'] as $file_id) {
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
						'group_id' => _db_int($gi)			
          );
          //print_r($t);
          _db_insert('mod_ftp',$t);
        }
      //}
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

  function get($id, $type='')
  {
    $sql = 'SELECT * FROM `'.DB_PREFIX.'mod_ftp` WHERE module_id='.intval($id).($type != '' ? ' AND item_type="'.$type.'"' : '');
    $res = array();
    $sql2 = 'SELECT * FROM `'.DB_PREFIX.'mod_ftp_options` WHERE module_id='.intval($id);
    $res = _db_get_one($sql2);
    $res['files'] = _db_get($sql);
    return $res;
  }

  function front($module, $Item)
  {
    $ftp = new File_Manager_System;
    $data_groups = $this->get($module['module_id'], 'g');
    $files = $this->get($module['module_id'], 'f');
    $style = $module['module_style'];
    
    if (!is_array($data_groups)) {
      $data_groups = array('files' => array());
    }
    
    if (!is_array($files)) {
      $files = array('files' => array());
    }
    
    foreach ($data_groups['files'] as $data_group) {
      $group[] = group_files($data_group['item_id']);
    }


		
		
		
		
		
		
		$BYLO = array();
    if (count($group) > 0) {
      global $GL_CONF;
      $cfg = $GL_CONF['IMAGES_FILES'];
      echo '<div class="width_site width_'.$data['module_id'].'"><div class="inside_content">';
      switch ($style) {
        case 0:	// lista
				echo '<div class="mod_ftp mod_ftp_0"><div class="margin">';
					if ($module['show_module_title']) {
						echo '<div class="mod_ftp_0_name mod_name">' . $module['module_name'] . '</div>';
					}
					echo '<ul class="files">';
          foreach ($group as $gr) {
            foreach ($gr as $file) {
						
							if ($file['file_title']) {
								$file_name = $file['file_title'];
							} else {
								$file_name = $file['file_name'];
							}
							echo '<li><a class="type_'.substr($file['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file['file_path'] . '/' . $file['file_name'] . '" target="_blank">' . $file_name .' <span class="desc"> '. $file['file_description'].'</span></a></li>';
						}
						
          }
					echo '<ul>';
          echo '</div></div>';
          break;
        case 1:	// lista w 2 kolumnach
				echo '<div class="mod_ftp mod_ftp_1"><div class="margin">';
					if ($module['show_module_title']) {
						echo '<div class="mod_ftp_1_name mod_name">' . $module['module_name'] . '</div>';
					}
				
          foreach ($group as $gr) {
            foreach ($gr as $file) {
						
							if ($file['file_title']) {
								$file_name = $file['file_title'];
							} else {
								$file_name = $file['file_name'];
							}
							echo '<a class="type_'.substr($file['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file['file_path'] . '/' . $file['file_name'] . '" target="_blank">' . $file_name .' <span class="desc"> '. $file['file_description'].'</span></a>';
						}
          }
          echo '</div></div>';
          break;
        case 2:	// lista w 3 kolumnach
				echo '<div class="mod_ftp mod_ftp_2"><div class="margin">';
					if ($module['show_module_title']) {
						echo '<div class="mod_ftp_2_name mod_name">' . $module['module_name'] . '</div>';
					}
				
          foreach ($group as $gr) {
            foreach ($gr as $file) {
						
							if ($file['file_title']) {
								$file_name = $file['file_title'];
							} else {
								$file_name = $file['file_name'];
							}
							echo '<a class="type_'.substr($file['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file['file_path'] . '/' . $file['file_name'] . '" target="_blank">' . $file_name .' <span class="desc"> '. $file['file_description'].'</span></a>';
						}
          }
          echo '</div></div>';
          break;
        case 3:	// lista w 4 kolumnach
					echo '<div class="mod_ftp mod_ftp_3"><div class="margin">';
					if ($module['show_module_title']) {
						echo '<div class="mod_ftp_3_name mod_name">' . $module['module_name'] . '</div>';
					}
					
          foreach ($group as $gr) {
            foreach ($gr as $file) {
							echo '<a class="type_'.substr($file['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file['file_path'] . '/' . $file['file_name'] . '" target="_blank">' . $file_name .' <span class="desc"> '. $file['file_description'].'</span></a>';
						}
          }
          echo '</div></div>';
          break;
        case 4:	// lista 1/4 strony
					echo '<div class="mod_ftp mod_ftp_4"><div class="margin">';
					if ($module['show_module_title']) {
						echo '<div class="mod_ftp_4_name mod_name">' . $module['module_name'] . '</div>';
					}
					
          foreach ($group as $gr) {
            foreach ($gr as $file) {
							echo '<a class="type_'.substr($file['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file['file_path'] . '/' . $file['file_name'] . '" target="_blank">' . $file_name .' <span class="desc"> '. $file['file_description'].'</span></a>';
						}
          }
          echo '</div></div>';
          break;
        case 5:	// lisa 1/3 strony
				echo '<div class="mod_ftp mod_ftp_5"><div class="margin">';
					if ($module['show_module_title']) {
						echo '<div class="mod_ftp_5_name mod_name">' . $module['module_name'] . '</div>';
					}
					
          foreach ($group as $gr) {
            foreach ($gr as $file) {
						
							if ($file['file_title']) {
								$file_name = $file['file_title'];
							} else {
								$file_name = $file['file_name'];
							}
							echo '<a class="type_'.substr($file['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file['file_path'] . '/' . $file['file_name'] . '" target="_blank">' . $file_name .' <span class="desc"> '. $file['file_description'].'</span></a>';
						}
          }
          echo '</div></div>';
          break;
        case 6:	// lista 1/2 strony
				echo '<div class="mod_ftp mod_ftp_6"><div class="margin">';
					if ($module['show_module_title']) {
						echo '<div class="mod_ftp_6_name mod_name">' . $module['module_name'] . '</div>';
					}
					
          foreach ($group as $gr) {
            foreach ($gr as $file) {
						
							if ($file['file_title']) {
								$file_name = $file['file_title'];
							} else {
								$file_name = $file['file_name'];
							}
							echo '<a class="type_'.substr($file['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file['file_path'] . '/' . $file['file_name'] . '" target="_blank">' . $file_name .' <span class="desc"> '. $file['file_description'].'</span></a>';
						}
          }
          echo '</div></div>';
          break;
        case 7:	// lista
				echo '<div class="mod_ftp mod_ftp_7"><div class="margin">';
					if ($module['show_module_title']) {
						echo '<div class="mod_ftp_7_name mod_name">' . $module['module_name'] . '</div>';
					}
					
          foreach ($group as $gr) {
            foreach ($gr as $file) {
						
							if ($file['file_title']) {
								$file_name = $file['file_title'];
							} else {
								$file_name = $file['file_name'];
							}
							echo '<a class="type_'.substr($file['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file['file_path'] . '/' . $file['file_name'] . '" target="_blank">' . $file_name .' <span class="desc"> '. $file['file_description'].'</span></a>';
						}
          }
          echo '</div></div>';
          break;
        case 8:	// lista
				echo '<div class="mod_ftp mod_ftp_8"><div class="margin">';
					if ($module['show_module_title']) {
						echo '<div class="mod_ftp_8_name mod_name">' . $module['module_name'] . '</div>';
					}
				
          foreach ($group as $gr) {
            foreach ($gr as $file) {
						
							if ($file['file_title']) {
								$file_name = $file['file_title'];
							} else {
								$file_name = $file['file_name'];
							}
							echo '<a class="type_'.substr($file['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file['file_path'] . '/' . $file['file_name'] . '" target="_blank">' . $file_name .' <span class="desc"> '. $file['file_description'].'</span></a>';
						}
          }
          echo '</div></div>';
          break;
        case 9:	// lista
				echo '<div class="mod_ftp mod_ftp_9"><div class="margin">';
					if ($module['show_module_title']) {
						echo '<div class="mod_ftp_9_name mod_name">' . $module['module_name'] . '</div>';
					}
				
          foreach ($group as $gr) {
            foreach ($gr as $file) {
						
							if ($file['file_title']) {
								$file_name = $file['file_title'];
							} else {
								$file_name = $file['file_name'];
							}
							echo '<a class="type_'.substr($file['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file['file_path'] . '/' . $file['file_name'] . '" target="_blank">' . $file_name .' <span class="desc"> '. $file['file_description'].'</span></a>';
						}
          }
          echo '</div></div>';
          break;
			
      }
    echo '</div></div>';  
      
    }
    
    if (count($files['files']) > 0) {
    echo '<div class="width_site width_'.$data['module_id'].'"><div class="inside_content">';
      switch ($style)	{
        case 0:	// lista w 2 kolumnach
          echo '<div class="mod_ftp mod_ftp_0">';
          foreach ($files['files'] as $file) {
            if (!empty($BYLO[$file['item_id'] . '-' . $file['group_id']])) {
              continue;
            }
            $BYLO[$file['item_id'] . '-' . $file['group_id']] = 1;
            $file_info = get_file_info($file['item_id'], $file['group_id']);
            $file_title = $file_info[0]['file_title']=='' ? $file_info[0]['file_name'] : $file_info[0]['file_title'];
						$descr = $file_info[0]['file_description'];
            echo '<a class="type_'.substr($file_info[0]['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file_info[0]['file_path'] . '/' . $file_info[0]['file_name'] . '" target="_blank">' . $file_title .' <span class="desc">'. $descr .'</span></a>';
            
          }
          break;
        case 1:	// lista w 3 kolumnach 
          echo '<div class="mod_ftp mod_ftp_1">';
          foreach ($files['files'] as $file) {
            if (!empty($BYLO[$file['item_id'] . '-' . $file['group_id']])) {
              continue;
            }
            $BYLO[$file['item_id'] . '-' . $file['group_id']] = 1;
            $file_info = get_file_info($file['item_id'], $file['group_id']);
            $file_title = $file_info[0]['file_title']=='' ? $file_info[0]['file_name'] : $file_info[0]['file_title'];
						$descr = $file_info[0]['file_description'];
            echo '<a class="type_'.substr($file_info[0]['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file_info[0]['file_path'] . '/' . $file_info[0]['file_name'] . '" target="_blank">' . $file_title .' <span class="desc">'. $descr .'</span></a>';
            
          } 
        case 2:	// lista w 4 kolumnach 
          echo '<div class="mod_ftp mod_ftp_2">';
          foreach ($files['files'] as $file) {
            if (!empty($BYLO[$file['item_id'] . '-' . $file['group_id']])) {
              continue;
            }
            $BYLO[$file['item_id'] . '-' . $file['group_id']] = 1;
            $file_info = get_file_info($file['item_id'], $file['group_id']);
            $file_title = $file_info[0]['file_title']=='' ? $file_info[0]['file_name'] : $file_info[0]['file_title'];
						$descr = $file_info[0]['file_description'];
            echo '<a class="type_'.substr($file_info[0]['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file_info[0]['file_path'] . '/' . $file_info[0]['file_name'] . '" target="_blank">' . $file_title .' <span class="desc">'. $descr .'</span></a>';
            
          }
          break;
        case 3:	// lista 1/4 strony
          echo '<div class="mod_ftp mod_ftp_3">';
          foreach ($files['files'] as $file) {
            if (!empty($BYLO[$file['item_id'] . '-' . $file['group_id']])) {
              continue;
            }
            $BYLO[$file['item_id'] . '-' . $file['group_id']] = 1;
            $file_info = get_file_info($file['item_id'], $file['group_id']);
            $file_title = $file_info[0]['file_title']=='' ? $file_info[0]['file_name'] : $file_info[0]['file_title'];
						$descr = $file_info[0]['file_description'];
            echo '<a class="type_'.substr($file_info[0]['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file_info[0]['file_path'] . '/' . $file_info[0]['file_name'] . '" target="_blank">' . $file_title .' <span class="desc">'. $descr .'</span></a>';
            
          }
          break;
        case 4:	// lista 1/3 strony
          echo '<div class="mod_ftp mod_ftp_4">';
          foreach ($files['files'] as $file) {
            if (!empty($BYLO[$file['item_id'] . '-' . $file['group_id']])) {
              continue;
            }
            $BYLO[$file['item_id'] . '-' . $file['group_id']] = 1;
            $file_info = get_file_info($file['item_id'], $file['group_id']);
            $file_title = $file_info[0]['file_title']=='' ? $file_info[0]['file_name'] : $file_info[0]['file_title'];
						$descr = $file_info[0]['file_description'];
            echo '<a class="type_'.substr($file_info[0]['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file_info[0]['file_path'] . '/' . $file_info[0]['file_name'] . '" target="_blank">' . $file_title .' <span class="desc">'. $descr .'</span></a>';
            
          }
        case 5:	// -------------
          echo '<div class="mod_ftp mod_ftp_5">';
          foreach ($files['files'] as $file) {
            if (!empty($BYLO[$file['item_id'] . '-' . $file['group_id']])) {
              continue;
            }
            $BYLO[$file['item_id'] . '-' . $file['group_id']] = 1;
            $file_info = get_file_info($file['item_id'], $file['group_id']);
            $file_title = $file_info[0]['file_title']=='' ? $file_info[0]['file_name'] : $file_info[0]['file_title'];
						$descr = $file_info[0]['file_description'];
            echo '<a class="type_'.substr($file_info[0]['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file_info[0]['file_path'] . '/' . $file_info[0]['file_name'] . '" target="_blank">' . $file_title .' <span class="desc">'. $descr .'</span></a>';
            
          }
          break;
        case 6:	// ----------------
          echo '<div class="mod_ftp mod_ftp_3">';
          foreach ($files['files'] as $file) {
            if (!empty($BYLO[$file['item_id'] . '-' . $file['group_id']])) {
              continue;
            }
            $BYLO[$file['item_id'] . '-' . $file['group_id']] = 1;
            $file_info = get_file_info($file['item_id'], $file['group_id']);
            $file_title = $file_info[0]['file_title']=='' ? $file_info[0]['file_name'] : $file_info[0]['file_title'];
						$descr = $file_info[0]['file_description'];
            echo '<a class="type_'.substr($file_info[0]['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file_info[0]['file_path'] . '/' . $file_info[0]['file_name'] . '" target="_blank">' . $file_title .' <span class="desc">'. $descr .'</span></a>';
            
          }
          break;
        case 7:	// ------------------
          echo '<div class="mod_ftp mod_ftp_4">';
          foreach ($files['files'] as $file) {
            if (!empty($BYLO[$file['item_id'] . '-' . $file['group_id']])) {
              continue;
            }
            $BYLO[$file['item_id'] . '-' . $file['group_id']] = 1;
            $file_info = get_file_info($file['item_id'], $file['group_id']);
            $file_title = $file_info[0]['file_title']=='' ? $file_info[0]['file_name'] : $file_info[0]['file_title'];
						$descr = $file_info[0]['file_description'];
            echo '<a class="type_'.substr($file_info[0]['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file_info[0]['file_path'] . '/' . $file_info[0]['file_name'] . '" target="_blank">' . $file_title .' <span class="desc">'. $descr .'</span></a>';
            
          }
        case 8:	// ---------------
          echo '<div class="mod_ftp mod_ftp_5">';
          foreach ($files['files'] as $file) {
            if (!empty($BYLO[$file['item_id'] . '-' . $file['group_id']])) {
              continue;
            }
            $BYLO[$file['item_id'] . '-' . $file['group_id']] = 1;
            $file_info = get_file_info($file['item_id'], $file['group_id']);
            $file_title = $file_info[0]['file_title']=='' ? $file_info[0]['file_name'] : $file_info[0]['file_title'];
						$descr = $file_info[0]['file_description'];
            echo '<a class="type_'.substr($file_info[0]['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file_info[0]['file_path'] . '/' . $file_info[0]['file_name'] . '" target="_blank">' . $file_title .' <span class="desc">'. $descr .'</span></a>';
            
          }
          break;
        case 9:	// -------------
          echo '<div class="mod_ftp mod_ftp_5">';
          foreach ($files['files'] as $file) {
            if (!empty($BYLO[$file['item_id'] . '-' . $file['group_id']])) {
              continue;
            }
            $BYLO[$file['item_id'] . '-' . $file['group_id']] = 1;
            $file_info = get_file_info($file['item_id'], $file['group_id']);
            $file_title = $file_info[0]['file_title']=='' ? $file_info[0]['file_name'] : $file_info[0]['file_title'];
						$descr = $file_info[0]['file_description'];
            echo '<a class="type_'.substr($file_info[0]['file_ext'], 1).'" href="' . $ftp->mainDirectory . $file_info[0]['file_path'] . '/' . $file_info[0]['file_name'] . '" target="_blank">' . $file_title .' <span class="desc">'. $descr .'</span></a>';
            
          }
          break;
      }
			echo '</div>';
    }
        echo '</div></div>';  
  }

}
