<?php 
if (!defined('_APP')) {
  exit; 
}
if (defined('_LIB_TRANS.PHP')) {
  return; 
}
define('_LIB_TRANS.PHP', 1);

function trans_update($tab, $id=0)
{
  global $GL_CONF;

  //reset($trans);
  $text_id=$id;

  if($text_id==0) {
    $text_id =trans_new_id();
  }

  foreach($GL_CONF['LANG'] as $lang=>$v) {
    $q = array(
			'trans_id'=>_db_int($text_id),
			'lang_id'=>_db_string($lang),
			'trans'=>_db_string($tab[$lang]),
    );
    _db_replace('trans',$q);
  }
  return $text_id;
}

function trans_delete($id)
{
  return _db_delete('trans','trans_id='.intval($id));
}

function trans_new_id()
{
  $res = _db_get_one('SELECT MAX(trans_id) AS num FROM `'.DB_PREFIX.'trans`');
  return $res['num']+1;
}

function trans_get($id)
{
  $res = array();
  $query = 'SELECT * FROM `'.DB_PREFIX.'trans` WHERE trans_id='.intval($id);
  $result = mysql_query($query);
  _debug(mysql_error(),$query);
  if($result) {
    while($row = mysql_fetch_assoc($result)) {
      $res[$row['lang_id']] = $row['trans'];
    }
    mysql_free_result($result);
  }
  return $res;
}

function trans_get_by_ids($list)
{
  $res = array();
  if(is_array($list) && count($list)>0) {
    $list = implode(',',$list);
  }
  if($list!='') {
    $query = 'SELECT * FROM `'.DB_PREFIX.'trans` WHERE trans_id IN ('._db_sqlspecialchars($list).')';
    $result = mysql_query($query);
    _debug(mysql_error(),$query);
    if($result) {
      while($row = mysql_fetch_assoc($result)) {
        if(!is_array($res[$row['trans_id']])) {
          $res[$row['trans_id']] = array();
        }
        $res[$row['trans_id']][$row['lang_id']] = $row['trans'];
      }
      mysql_free_result($result);
    }
  }
  return $res;
}

function trans_get_by_ids_lang($list, $lang='')
{
  if($lang=='') {
    return trans_get_by_ids($list);
  } else {
    $res = array();
    if(is_array($list) && count($list)>0) {
      $list = implode(',',$list);
    }
    if($list!='') {
      $query = 'SELECT * FROM `'.DB_PREFIX.'trans` WHERE trans_id IN ('._db_sqlspecialchars($list).') AND lang=\''._db_sqlspecialchars($lang).'\'';
      $result = mysql_query($query);
      _debug(mysql_error(),$query);
      if($result) {
        while($row = mysql_fetch_assoc($result)) {
          if(!is_array($res[$row['trans_id']])) {
            $res[$row['trans_id']] = array();
          }
          $res[$row['trans_id']] = $row['trans'];
        }
        mysql_free_result($result);
      }
    }
    return $res;
  }
}