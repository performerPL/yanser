<?php
if (!defined('_APP')) {
  exit;
}
if (defined('_LIB_REPO.PHP')) {
  return;
}
define('_LIB_REPO.PHP', 1);

function repo_list($type, $dir)
{
  global $GL_CONF;
  // jesli type>0 to trzeba ograniczyć wybór tylko do obrazków
  $res = array();
  $dirs=  array();

  if ($dir{strlen($dir)-1}!='/') {
    $dir .= '/';
  }
  $rel_dir = ($type==FILE_ANY)?str_replace(REPOSITORY,'',$dir):str_replace(REPO_IMAGES,'',$dir);

  $show_ext = ($type==FILE_ANY)?$GL_CONF['UPLOAD_FILES']:$GL_CONF['UPLOAD_IMAGES'];

  if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) {
      if ($file{0} != '.') {
        if (!is_dir($dir.$file)) {
          $ext = substr($file,strrpos($file,'.')+1);
          if (is_dir($dir.$file)|| in_array(strtolower($ext),$show_ext)) {
            $res[] = array('rel_path'=>$rel_dir.$file,'file_name'=>$file,'file_icon'=>repo_icon($ext),'is_dir'=>false);
          }
        } else {
          $dirs[] = array('rel_path'=>$rel_dir.$file,'file_name'=>$file,'file_icon'=>repo_icon(),'is_dir'=>true);
          //array_unshift($res,array('file_name'=>$file,'file_icon'=>repo_icon(),'is_dir'=>true));
        }
      }
    }
  }
  for ($i=count($dirs)-1,$lim=0;$i>=$lim;--$i) {
    array_unshift($res,$dirs[$i]);
  }
  //var_dump($res);
  return $res;
}

function repo_icon($ext='')
{
  //echo $ext;
  $res = 'empty.png';
  if ($ext=='') {
    $res = 'folder.png';
  } else {
    switch (strtolower($ext)) {
      case 'gif':
      case 'jpg':
      case 'jpeg':
      case 'png':
      case 'psd':
      case 'xcf':
      case 'bmp':
      case 'tiff':
        $res = 'image.png';
        break;

      default:
        $res = 'empty.png';
    }
  }
  return 'img/file/' . $res;
}
