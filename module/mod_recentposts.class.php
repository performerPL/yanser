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

  protected function getTopics($count, $t_id, $f_id, $what)
  {
    $sql = 'SELECT * FROM minibbtable_posts pp ';
    $sql .= 'JOIN minibbtable_topics pt ON (pp.topic_id = pt.topic_id) ';
    
    if ($t_id > 0 || $f_id > 0) {
      $sql .= 'WHERE ';
      $where = false;
      if ($t_id > 0) {
        $sql .= 'pp.topic_id = ' . _db_int($t_id) . ' ';
        $where = true;
      }
      if ($f_id > 0) {
        if ($where) {
          $sql .= 'AND ';
        }
        $sql .= 'pp.forum_id = ' . _db_int($f_id) . ' ';
      }
    }

    if ($what == 1) {
      $sql .= 'ORDER BY pp.post_time DESC ';
    } elseif ($what == 2) {
      $sql .= 'GROUP BY pp.topic_id ';
      $sql .= 'ORDER BY pt.topic_views DESC ';
    }
    $sql .= 'LIMIT ' . intval($count);
    
    $R = _db_get($sql,'',null,false);
    return $R;
  }

  function front($module, $Item)
  {
    $MODULE = $this->get($module['module_id']);

    $TOPICS = array();

    $TOPICS = $this->getTopics($MODULE['count'], $MODULE['topic_id'], $MODULE['forum_id'], $MODULE['what']);

    foreach ($TOPICS as $V) {
      //echo '<a href="forum/index.php?action=vthread&forum=' . $V['forum_id'] . '&topic=' . $V['topic_id'] . '">' . $V['topic_title'] . '</a>';
      //MArcin: skrocony adres po wlaczeniu rewrite
    	echo '<a href="forum/' . $V['forum_id'] . '_' . $V['topic_id'] . '_0.html" title="' . $V['topic_title'] . '">' . $V['topic_title'] . '</a>';
    }
  }
}
