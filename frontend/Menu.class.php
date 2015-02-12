<?php
if (!defined('_APP')) {
  exit;
}

require_once 'lib/menu.php';
require_once 'lib/item.php';

class Menu extends ItemTree
{
  private
  $ADDONS = array();

  function __construct($name, $parent=0, $level=1, $what='menu',$simpleTab = true)
  {
    parent::__construct($name, $parent, $level, $what,$simpleTab);
    $this->fetchAddons($name);
  }

  /**
   * pobierz zmienna z bazy danych
   *
   * @param mixed $name
   */
  function fetchAddons($name)
  {
    $res = _db_get_one('SELECT menu_id FROM ' . DB_PREFIX . 'menu WHERE menu_code = ' . _db_string($name) . ' LIMIT 1');
    $ADDONS = _db_get('SELECT name, value FROM ' . DB_PREFIX . 'menu_addons WHERE menu_id = ' . _db_int($res['menu_id']));
    foreach ($ADDONS as $k => $V) {
      $this->ADDONS[$V['name']] = $V['value'];
    }
  }

  /**
   * Pobranie zmiennej ustalonej w panelu administracyjnym.
   *
   * @param mixed $i Nazwa zdefiniowana w pierwszym polu w edycji paska menu w panelu admina.
   * @return mixed Zwraca wartość przypisaną w drugim polu w edycji paska menu w panelu admina.
   */
  function getAddon($i)
  {
    if (!empty($this->ADDONS[$i])) {
      return $this->ADDONS[$i];
    }
    return null;
  }

  function printMenu1($parent=-1, $current = null)
  {
    //var_dump($this->data);
    $all = count($this->getItems($parent));
    $i = 0;
    $currentx = false;
    //var_dump($this->data[$parent]);
    foreach ($this->getItems($parent) as $id => $item) {
      $currentx = false;
      if (is_object($current) && $current->getID() == $item->getID()) {
        $currentx = true;
      }
      echo $item->getLink('', array(), $currentx);
      $i++;
      if ($i != $all) {
      echo '<span class="accessibility">&nbsp;&nbsp;|&nbsp;&nbsp;</span>'; // ta klasa powinna być domyślnie ukryta - po wyłączeniu css-ów linki będą oddzielone kreską, dla czytelności
      }

    }
  }

  function printTree($currentItem=false, $parent=-1, $level=0)
  {
    $clevel = 1;
    $this->printTreeRecursive($parent, $currentItem, $clevel, $level);
  }

  public function printSitemap($a=false, $b=-1, $c=0)
  {
    $this->printTree($a, $b, $c);
  }

  /**
   * menu z zakladkami
   *
   * @param Item $Item
   * @param int $parent
   * @param int $level
   */
  function printList($Item=false, $parent=-1, $level=0)
  {
    $clevel = 0;
    $this->printListRecursive($parent, $Item, $clevel, $level);
  }

  /**
   * proste menu
   *
   * @param int $parent
   */
  function printSimple($Item=false, $parent=-1)
  {
    $all = count($this->getItems($parent));
    $i = 0;
    foreach ($this->getItems($parent) as $id => $item) {
      $cur = false;
      if (is_object($Item) && ($id==$Item->getID() || is_object($Item->getHistory()->getByID($id)))) {
        $cur = true;
      }
      echo $item->getLink('', array(), $cur, $i);
      $i++;
      if ($i != $all) {
        echo '<span class="accessibility">&nbsp;&nbsp;|&nbsp;&nbsp;</span>'; // ta klasa powinna być domyślnie ukryta - po wyłączeniu css-ów linki będą oddzielone kreską, dla czytelności
      }
    }
  }

  public function generateGoogleMap($currentItem=false, $parent=-1, $level=0)
  {
    $clevel = 1;
    $this->printGoogleMap($parent, $currentItem, $clevel, $level);
  }

  public function printGoogleMap($parent=-1, $curItem, $curLevel, $maxLevel)
  {
    global $GL_CONF;
    $cfg = $GL_CONF['IMAGES_FILES'];
   /* $all = count($this->getItems($parent));
    $i = 0;
    foreach ($this->getItems($parent) as $id => $item) {
      echo "<url>\r\n";
      echo "<loc>" . htmlspecialchars($cfg['IMAGE_BASE_URL']) . $item->getMapLink() . "</loc>\r\n";
      echo "<lastmod>" . $item->getDateMod() . "</lastmod>\r\n";
      echo "</url>\r\n";
      $i++;
    }*/

    $tab = $this->getItemsAll();

    if (count($tab) > 0) {
      foreach ($tab as $id => $item) {
        // pomija linki zewnetrzne
      	if($item->getItemType() == 3)
        	continue;
      	if (is_object($curItem) && ($id==$curItem->getID() || is_object($curItem->getHistory()->getByID($id)))) {
          echo "<url>\r\n";
          echo "<loc>" . htmlspecialchars(MAIN_DOMAIN) .'/'. $item->getMapLink() . "</loc>\r\n";
          echo "<lastmod>" . substr($item->getDateMod(),0,10) . "</lastmod>\r\n";
          echo "<changefreq>daily</changefreq>";
          echo "<priority>". $item->getArticleOrder() . "</priority>\r\n";
          echo "</url>\r\n";
          if (($curLevel<$level && $level>0) || $level<=0) {
            $this->printListRecursive($id,$curItem,$curLevel+1,$maxLevel);
          }
        } else {
          echo "<url>\r\n";
          echo "<loc>" . htmlspecialchars(MAIN_DOMAIN) .'/'. $item->getMapLink() . "</loc>\r\n";
          echo "<lastmod>" . substr($item->getDateMod(),0,10) . "</lastmod>\r\n";
          echo "<changefreq>daily</changefreq>";
          echo "<priority>". $item->getArticleOrder() . "</priority>\r\n";
          echo "</url>\r\n";
        }
      }
    }
  }

  function printSimple_curr($parent=-1, $curr_id)
  {
    foreach ($this->getItems($parent) as $id => $item) {
      echo $curr_id;
      echo $item->getID();
      echo $item->getLink();
      echo '<span class="accessibility"> | </span>'; // ta klasa powinna być domyślnie ukryta - po wyłączeniu css-ów linki będą oddzielone kreską, dla czytelności
    }
  }

  /**
   * proste menu z ul i li
   *
   * @param int $parent
   */
  function printListSimple($Item=false, $parent=-1)
  {
    $tab = $this->getItems($parent);
    if (count($tab) > 0) {
      echo '<ul>';
      foreach ($tab as $id => $item) {

        echo '<li';
        if (is_object($Item) && ($id==$Item->getID() || is_object($Item->getHistory()->getByID($id)))) {
          echo ' class="current">' . $item->getLink() . '<span class="accessibility">&nbsp;&nbsp;|&nbsp;&nbsp;</span>';
        } else {
          echo '>' . $item->getLink() . '<span class="accessibility">&nbsp;&nbsp;|&nbsp;&nbsp;</span>';
        }
      }
      echo '</ul>';
    }
  }

  /**
   * lista stron z podstronami
   *
   * @param unknown_type $parent
   * @param unknown_type $curItem
   * @param unknown_type $curLevel
   * @param unknown_type $maxLevel
   */
  private function printListRecursive($parent, $curItem, $curLevel, $maxLevel)
  {
    $tab = $this->getItems($parent);
    if (count($tab) > 0) {
      echo '<ul>';
      foreach ($tab as $id => $item) {
        echo '<li';
        if (is_object($curItem) && ($id==$curItem->getID() || is_object($curItem->getHistory()->getByID($id)))) {
          echo ' class="current_'.$curLevel.' '.($item->getID()==$curItem->getID() ? 'current' : '').'">';
          echo '<div class="link link_'.$curLevel.'">'.$item->getLink().'</div>';
					//echo $item->getID();
					//echo $curItem->getID();
          if (($curLevel<$level && $level>0) || $level<=0) {
            echo '<div id="submenu_list_'.$curLevel.'">';
            $this->printListRecursive($id,$curItem,$curLevel+1,$maxLevel);
            echo '</div>';
          }
        } else {
          echo ' class="level_'.$curLevel.'">'.$item->getLink();
          //echo '<pre>'.var_dump($curItem->getHistory(),1).'</pre>';
        }
        echo '</li>';
      }
      echo '</ul>';
    }
  }

  private function printTreeRecursive($parent, $curItem, $curLevel, $maxLevel)
  {
    $tab = $this->getItems($parent);
    if (count($tab) > 0) {
      echo '<ul>';
      foreach ($tab as $id => $item) {
        echo '<li class="';
					if (is_object($curItem) && ($id==$curItem->getID() || is_object($curItem->getHistory()->getByID($id)))) {
						echo ' current ';
					 } else {
					  echo ' normal ';
					 }
        echo ' level_'.$curLevel.'">'.$item->getLink();
				//echo 'curlevel:'.$curLevel.', level:'.$maxLevel;
        if (($curLevel<$level && $level > 0) || $level <= 0) {
          $this->printTreeRecursive($id, $curItem, $curLevel + 1, $maxLevel);
        }
        echo '</li>';
      }
      echo '</ul>';
    }
  }
	
	//-----  NIESTANDARDCWE -------------------------------------------------------------------------------------------------------
	  /** 
   * menu z ikonami dla  meditour 
   *
   * @param int $parent
   */
  function printIconMenu($Item=false, $parent=-1)
  {
    $all = count($this->getItems($parent));
    $i = 0;
    foreach ($this->getItems($parent) as $id => $item) {
      $cur = false;
      if (is_object($Item) && ($id==$Item->getID() || is_object($Item->getHistory()->getByID($id)))) {
        $cur = true;
      }
			echo '<div class="menu_box">';
			echo '<a href=' . $item->getLinkUrl() . ' style="background: url('.$cfg['IMAGE_BASE_URL'] .$item->getIcon().') no-repeat top center;">';
			echo $item->getName();
			echo '</a>';
			//echo $item->getLink('', array(), $cur);
			echo '</div>';
    
    }
  }
	
	
	

  public function TreeMap ($currentItem=false, $parent=-1, $level=0)
  {
    $clevel = 1;
    $this->printTreeMap ($parent, $currentItem, $clevel, $level);
  }

  public function printTreeMap ($parent=-1, $curItem, $curLevel, $maxLevel)
  {
    global $GL_CONF;
    $cfg = $GL_CONF['IMAGES_FILES'];
    $tab = $this->getItemsAll();

    if (count($tab) > 0) {
      foreach ($tab as $id => $item) {
        // pomija linki zewnetrzne
      	if($item->getItemType() == 3)
        	continue;
      	if (is_object($curItem) && ($id==$curItem->getID() || is_object($curItem->getHistory()->getByID($id)))) {
					echo "<li>" . htmlspecialchars($cfg['IMAGE_BASE_URL']) . $item->getMapLink() . "</li>";
            if (($curLevel<$level && $level>0) || $level<=0) {
            $this->printListRecursive($id,$curItem,$curLevel+1,$maxLevel);
          }
        } else {
          echo "<li>" . htmlspecialchars($cfg['IMAGE_BASE_URL']) . $item->getMapLink() . "</li>";
        }
      }
    }
  }
	
	
	
	

}