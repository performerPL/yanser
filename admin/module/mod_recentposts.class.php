<?php
define('mod_recentposts.class', 1);

class mod_recentposts
{
  function update($tab)
  {
    $R = array(
      'module_id' => _db_int($tab['module_id']),
      'style' => _db_int($tab['style']),
      'forum_id' => _db_int($tab['forum_id']),
      'topic_id' => _db_int($tab['topic_id']),
      'what' => _db_int($tab['what']),
      'count' => _db_int($tab['count'])
    );
    return _db_replace('mod_recentposts', $R);
  }

  function remove($id)
  {
    return _db_delete('mod_recentposts', 'module_id='.intval($id), 1);
  }

  function validate($tab, $T)
  {
    return true;
  }

  function get($id)
  {
    return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_recentposts` WHERE module_id='.intval($id).' LIMIT 1');
  }

  function front($module, $Item)
  {

  }
}
