<?php

//if(defined('mod_subitems.class')) die('aa');
//	po stylach	if($module["module_style"] == 1)
//  if($options["show_icon"])	// po opcjach
 
define('mod_subitems.class', 1);

require_once 'module/Bean.class.php';

class mod_subitems extends Mod_Bean
{

	/* typ modulu */
	private $moduleType = 6;

	function update($tab)
	{
		$q = array(
			'subitems_id'=>_db_int($tab['module_id']),
			'show_icon'=>_db_bool($tab['show_icon']),
			'show_title'=>_db_bool($tab['show_title']),
			'show_description'=>_db_bool($tab['show_description']),
			'show_date'=>_db_bool($tab['show_date']),
			'show_date_mod'=>_db_bool($tab['show_date_mod']),
			'show_author'=>_db_int($tab['show_author']),
			'show_per_page'=>_db_int($tab['show_per_page']),
			'show_sort' => _db_int($tab['show_sort']),
			'show_popularity' => _db_int($tab['show_popularity']),
			'show_content' => _db_int($tab['show_content']),
			'show_article_id'=> _db_int($tab['show_article_id']),
			'show_subitems_counter' => _db_int($tab['show_subitems_counter']),
		);
		return _db_replace('mod_subitems', $q);
	}

	function remove($id)
	{
		return _db_delete('mod_subitems','subitems_id='.intval($id),1);
	}

	function validate($tab, $T)
	{
		return true;
	}

	function get($id)
	{
		return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_subitems` WHERE subitems_id='.intval($id).' LIMIT 1');
	}

	public function count($Item)
	{
		$count = 0;
		$subitems = item_get_orders($Item->getID());
		foreach ($subitems as $subitem_id) {
			$subitem = new Item($subitem_id['item_id']);
			if($subitem->isActive()) {
				$count++;
			}
		}
		return $count;
	}

	function front($module, $Item)
	{
		global $GL_CONF;
		$cfg = $GL_CONF['IMAGES_FILES'];

		
		// uaktualnia dane o module (dla ustawien recznych)
		$module = $this->getModuleContent($module['module_id'],$module);

		//print_r ($module);
		
		//echo '<br>';
		// wczytuje ustawienia dla modulu
		$SETTINGS = $this->get($module['module_id']);
		//print_r ($SETTINGS) ;
		
		
		//ID wyswitlanego artykulu
      $siteID = $Item->getID();

		// sprawdza czy sortowanie ma sie odbywac przez inny artykul
		if(!empty($module['sort_article_id'])) {
			$sortArticleId = $module['sort_article_id'];
		}
		else {
			$sortArticleId = $Item->getID();
		}


		// gdy zdefiniowany inny artykul w ustawieniach
		if(!empty($SETTINGS['show_article_id'])) {
			// tworzy nowy obiekt Item
			$Item = new Item((int)$SETTINGS['show_article_id'],false);
		}

		// gdy nie istnieje SORT_SUB, wczytuje ustawienia z modulu
		if(!isset($_GET['SORT_SUB']))
		$sortType = $SETTINGS['show_sort_type'];
		else
		$sortType = $_GET['SORT_SUB'];

		// lista z id sortowania znajduje sie w admin/modules/mod_subitems.class.php
		switch ($sortType) {
			case 1 : // dla 1 sortowanie po dacie utworzenia
				//			case 'date':
				$sb = 'i.created DESC';
				break;
			case 3 : // dla 1 sortowanie po dacie utworzenia - rosnaco
				$sb = 'i.created ASC';
				break;

			case 2 : // dla 2 sortowanie po liczniku ogladalnosci
				//			case 'counter':
				$sb = 'a.counter DESC';
				break;
			case 4 : // dla 2 sortowanie po liczniku ogladalnosci - rosnaco
				$sb = 'a.counter ASC';
				break;
					
			case 0 : // dla zera sortowanie po numerze porzadkowym
			default:
				$sb = 'i.item_order';
				break;
		}

		$subitems = item_get_orders($Item->getID(), 0, $sb);
		foreach ($subitems as $k => $V) {
			$subitem = new Item($V);
			if (!$subitem->isShowInSubitems() || !$subitem->isActive()) {
				unset($subitems[$k]);
			}
		}
		$style = $module['module_style'];

		//Zmiany stronnicowania 2014-05-20 - nowa wersja z przewijaniem
		//$limit = $SETTINGS['show_per_page'];
      $limit = 0;
      
		$offset = _db_int($_REQUEST['_subitems_offset']);
		if (!$offset) {
			$offset = 0;
		}

		if ($limit > 0) {
			$subitems = array_slice($subitems, $offset, $limit);
		}

		// Pokazuje linki do sortowania
		if ($SETTINGS['show_sort'] > 0) {
			echo '<div class="sort">
			<a href="'. $Item->getID() ;
			if($sortType != 1)
			echo '?SORT_SUB=1" ';
			else
			echo '?SORT_SUB=3" ';
			echo 'class="sort_link" rel="nofollow">Sortuj po dacie</a>&nbsp;&nbsp;|
			&nbsp;&nbsp;<a href="'. $Item->getID() ;
			if($sortType != 2)
			echo '?SORT_SUB=2" ';
			else
			echo '?SORT_SUB=4" ';
			echo 'class="sort_link" rel="nofollow">Sortuj po ogladalnosci</a>
			<div class="space"></div></div>';
		}

      
		switch ($style) {
			case 0: // 
			   
			   echo '<div class="width_site width_'.$module['module_id'].'"><div class="inside_content">';
				echo '<ul class="mod_subitems mod_subitems_0  mod_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-1-1 '.($subitem->getID() == $siteID ? 'current' : '').'"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date'] > 0) {
							echo ' <span class="date">(' . $subitem->getDate() ;
							// znacznik popularnosci artykulu
							if($SETTINGS['show_popularity'] == 1)
							echo '; czytany '. $subitem->getArticleCounter() . ' razy )</span>';
						}
						// ilosc podstron
						if($SETTINGS['show_subitems_counter'] > 0) {	echo ' <span class="subchild">('.$subitem->getCountChild().')</span>'; 	}
						echo '</div>';
					}
					//OPIS
					if ($SETTINGS['show_description'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
					//ZAWARTOSC STRONY
					if ($SETTINGS['show_content'] > 0) {	echo '<div class="desc">'.$subitem->getContent().'</div>';		}
					// sprawdza czy pokazac autora i zródlo
					// pokazuje jesli zaznaczono globalnie dla modulu lub indywidualnie dla itemu w opcjach widocznosci
					if( ($SETTINGS['show_author'] == 1) || ( ($SETTINGS['show_author'] == 2) && ($subitem->isShowAuthor()) ) ) {  echo '<div class="author"><b>wiecej na:&nbsp; ' . $subitem->getAuthorSource() . '</b> (' . $subitem->getAuthor() . ')</div>';	}
					if ($SETTINGS['show_date_mod'] > 0) {	echo '<div class="date_mod">' . $subitem->getDateMod() . '</div>';	}
					echo '<div class="space"></div>';
					echo "</div></a></div></li>";
					$z++;
				}
				echo '</ul>';
				echo '<div class="space"></div>';
				echo '</div></div>';
				break;
				
			case 1: // 2 kolumny
			   echo '<div class="width_site width_'.$module['module_id'].' "><div class="inside_content">';
				echo '<ul class="mod_subitems mod_subitems_1  mod_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-1-2 '.($subitem->getID() == $siteID ? 'current' : '').'"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date'] > 0) {
							echo ' <span class="date">(' . $subitem->getDate() ;
							// znacznik popularnosci artykulu
							if($SETTINGS['show_popularity'] == 1)
							echo '; czytany '. $subitem->getArticleCounter() . ' razy )</span>';
						}
						// ilosc podstron
						if($SETTINGS['show_subitems_counter'] > 0) {	echo ' <span class="subchild">('.$subitem->getCountChild().')</span>'; 	}
						echo '</div>';
					}
					//OPIS
					if ($SETTINGS['show_description'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
					//ZAWARTOSC STRONY
					if ($SETTINGS['show_content'] > 0) {	echo '<div class="desc">'.$subitem->getContent().'</div>';		}
					// sprawdza czy pokazac autora i zródlo
					// pokazuje jesli zaznaczono globalnie dla modulu lub indywidualnie dla itemu w opcjach widocznosci
					if( ($SETTINGS['show_author'] == 1) || ( ($SETTINGS['show_author'] == 2) && ($subitem->isShowAuthor()) ) ) {  echo '<div class="author"><b>wiecej na:&nbsp; ' . $subitem->getAuthorSource() . '</b> (' . $subitem->getAuthor() . ')</div>';	}
					if ($SETTINGS['show_date_mod'] > 0) {	echo '<div class="date_mod">' . $subitem->getDateMod() . '</div>';	}
					echo '<div class="space"></div>';
					echo "</div></a></div></li>";
					$z++;
				}
				echo '</ul>';
				echo '<div class="space"></div>';
				echo '</div></div>';
				break;

				case 2: // 1-3
				echo '<div class="width_site width_'.$module['module_id'].'"><div class="inside_content">';
				echo '<ul class="mod_subitems mod_subitems_3  mod_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-1-3"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date'] > 0) {
							echo ' <span class="date">(' . $subitem->getDate() ;
							// znacznik popularnosci artykulu
							if($SETTINGS['show_popularity'] == 1)
							echo '; czytany '. $subitem->getArticleCounter() . ' razy )</span>';
						}
						// ilosc podstron
						if($SETTINGS['show_subitems_counter'] > 0) {	echo ' <span class="subchild">('.$subitem->getCountChild().')</span>'; 	}
						echo '</div>';
					}
					//OPIS
					if ($SETTINGS['show_description'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
					//ZAWARTOSC STRONY
					if ($SETTINGS['show_content'] > 0) {	echo '<div class="desc">'.$subitem->getContent().'</div>';		}
					// sprawdza czy pokazac autora i zródlo
					// pokazuje jesli zaznaczono globalnie dla modulu lub indywidualnie dla itemu w opcjach widocznosci
					if( ($SETTINGS['show_author'] == 1) || ( ($SETTINGS['show_author'] == 2) && ($subitem->isShowAuthor()) ) ) {  echo '<div class="author"><b>wiecej na:&nbsp; ' . $subitem->getAuthorSource() . '</b> (' . $subitem->getAuthor() . ')</div>';	}
					if ($SETTINGS['show_date_mod'] > 0) {	echo '<div class="date_mod">' . $subitem->getDateMod() . '</div>';	}
					echo '<div class="space"></div>';
					echo "</div></a></div></li>";
					$z++;
				}
				echo '</ul>';
				echo '<div class="space"></div>';
				echo '</div></div>';
				break;

			case 3: // 4 kolumny
			echo '<div class="width_site width_'.$module['module_id'].'"><div class="inside_content">';
				echo '<ul class="mod_subitems mod_subitems_4  mod_'.$module['module_id'].' loop" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-1-4 '.($subitem->getID() == $siteID ? 'current' : '').'"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date'] > 0) {
							echo ' <span class="date">(' . $subitem->getDate() ;
							// znacznik popularnosci artykulu
							if($SETTINGS['show_popularity'] == 1)
							echo '; czytany '. $subitem->getArticleCounter() . ' razy )</span>';
						}
						// ilosc podstron
						if($SETTINGS['show_subitems_counter'] > 0) {	echo ' <span class="subchild">('.$subitem->getCountChild().')</span>'; 	}
						echo '</div>';
					}
					//OPIS
					if ($SETTINGS['show_description'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
					//ZAWARTOSC STRONY
					if ($SETTINGS['show_content'] > 0) {	echo '<div class="desc">'.$subitem->getContent().'</div>';		}
					// sprawdza czy pokazac autora i zródlo
					// pokazuje jesli zaznaczono globalnie dla modulu lub indywidualnie dla itemu w opcjach widocznosci
					if( ($SETTINGS['show_author'] == 1) || ( ($SETTINGS['show_author'] == 2) && ($subitem->isShowAuthor()) ) ) {  echo '<div class="author"><b>wiecej na:&nbsp; ' . $subitem->getAuthorSource() . '</b> (' . $subitem->getAuthor() . ')</div>';	}
					if ($SETTINGS['show_date_mod'] > 0) {	echo '<div class="date_mod">' . $subitem->getDateMod() . '</div>';	}
					echo '<div class="space"></div>';
					echo '</div></a><div class="space"></div></div><div class="space"></div></li>';
					$z++;
				}
				echo '</ul>';
				echo '<div class="space"></div>';
				echo '</div></div>';
				break;


			case 4: // 6 kolumn
			echo '<div class="width_site width_'.$module['module_id'].'"><div class="inside_content">';
				echo '<ul class="mod_subitems mod_subitems_4  mod_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-1-6 '.($subitem->getID() == $siteID ? 'current' : '').'"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date'] > 0) {
							echo ' <span class="date">(' . $subitem->getDate() ;
							// znacznik popularnosci artykulu
							if($SETTINGS['show_popularity'] == 1)
							echo '; czytany '. $subitem->getArticleCounter() . ' razy )</span>';
						}
						// ilosc podstron
						if($SETTINGS['show_subitems_counter'] > 0) {	echo ' <span class="subchild">('.$subitem->getCountChild().')</span>'; 	}
						echo '</div>';
					}
					//OPIS
					if ($SETTINGS['show_description'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
					//ZAWARTOSC STRONY
					if ($SETTINGS['show_content'] > 0) {	echo '<div class="desc">'.$subitem->getContent().'</div>';		}
					// sprawdza czy pokazac autora i zródlo
					// pokazuje jesli zaznaczono globalnie dla modulu lub indywidualnie dla itemu w opcjach widocznosci
					if( ($SETTINGS['show_author'] == 1) || ( ($SETTINGS['show_author'] == 2) && ($subitem->isShowAuthor()) ) ) {  echo '<div class="author"><b>wiecej na:&nbsp; ' . $subitem->getAuthorSource() . '</b> (' . $subitem->getAuthor() . ')</div>';	}
					if ($SETTINGS['show_date_mod'] > 0) {	echo '<div class="date_mod">' . $subitem->getDateMod() . '</div>';	}
					echo '<div class="space"></div>';
					echo "</div></a></div></li>";
					$z++;
				}
				echo '</ul>';
				echo '<div class="space"></div>';
				echo '</div></div>';
				break;

			case 5: // 8 kolumn
			echo '<div class="width_site width_'.$module['module_id'].'"><div class="inside_content">';
				echo '<ul class="mod_subitems mod_subitems_5  mod_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-1-8 '.($subitem->getID() == $siteID ? 'current' : '').'"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date'] > 0) {
							echo ' <span class="date">(' . $subitem->getDate() ;
							// znacznik popularnosci artykulu
							if($SETTINGS['show_popularity'] == 1)
							echo '; czytany '. $subitem->getArticleCounter() . ' razy )</span>';
						}
						// ilosc podstron
						if($SETTINGS['show_subitems_counter'] > 0) {	echo ' <span class="subchild">('.$subitem->getCountChild().')</span>'; 	}
						echo '</div>';
					}
					//OPIS
					if ($SETTINGS['show_description'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
					//ZAWARTOSC STRONY
					if ($SETTINGS['show_content'] > 0) {	echo '<div class="desc">'.$subitem->getContent().'</div>';		}
					// sprawdza czy pokazac autora i zródlo
					// pokazuje jesli zaznaczono globalnie dla modulu lub indywidualnie dla itemu w opcjach widocznosci
					if( ($SETTINGS['show_author'] == 1) || ( ($SETTINGS['show_author'] == 2) && ($subitem->isShowAuthor()) ) ) {  echo '<div class="author"><b>wiecej na:&nbsp; ' . $subitem->getAuthorSource() . '</b> (' . $subitem->getAuthor() . ')</div>';	}
					if ($SETTINGS['show_date_mod'] > 0) {	echo '<div class="date_mod">' . $subitem->getDateMod() . '</div>';	}
					echo '<div class="space"></div>';
					echo "</div></a></div></li>";
					$z++;
				}
				echo '</ul>';
				echo '<div class="space"></div>';
				echo '</div></div>';
				break;

			case 6: // przewijanie cała strona
			
		
			
			
			
			
			
			
			echo '<div class="width_site width_'.$module['module_id'].'"><div class="inside_content">';
			echo '<div class="mod_subitems_scroll">';
			echo '<ul class="mod_subitems_scroll  loop_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-scroll '.($subitem->getID() == $siteID ? 'current' : '').'"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date'] > 0) {
							echo ' <span class="date">(' . $subitem->getDate() ;
							// znacznik popularnosci artykulu
							if($SETTINGS['show_popularity'] == 1)
							echo '; czytany '. $subitem->getArticleCounter() . ' razy )</span>';
						}
						// ilosc podstron
						if($SETTINGS['show_subitems_counter'] > 0) {	echo ' <span class="subchild">('.$subitem->getCountChild().')</span>'; 	}
						echo '</div>';
					}
					//OPIS
					if ($SETTINGS['show_description'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
					//ZAWARTOSC STRONY
					if ($SETTINGS['show_content'] > 0) {	echo '<div class="desc">'.$subitem->getContent().'</div>';		}
					// sprawdza czy pokazac autora i zródlo
					// pokazuje jesli zaznaczono globalnie dla modulu lub indywidualnie dla itemu w opcjach widocznosci
					if( ($SETTINGS['show_author'] == 1) || ( ($SETTINGS['show_author'] == 2) && ($subitem->isShowAuthor()) ) ) {  echo '<div class="author"><b>wiecej na:&nbsp; ' . $subitem->getAuthorSource() . '</b> (' . $subitem->getAuthor() . ')</div>';	}
					if ($SETTINGS['show_date_mod'] > 0) {	echo '<div class="date_mod">' . $subitem->getDateMod() . '</div>';	}
					echo '<div class="space"></div>';
					echo '</div></a></div><div class="space"></div></li>';
					$z++;
				}
				echo '</ul>';
				echo '<div class="space"></div></div>';
				echo '</div></div>';
				?>
				
    <script>
    $(document).ready(function() {
      $("#<? echo 'mod_'.$module['module_id']; ?>").owlCarousel({
        autoPlay: 3000,
        items : <? echo $SETTINGS['show_per_page']; ?>,
                 		center: false,
               		autoplay:true,
	                  autoplayTimeout:3000,
               		loop:true,
               		navigation : true,
        
      });

    });
    </script>
				
				

				<?
				break;
				
			case 7: // przewijanie 3/4 strony

			echo '<div class="mod_subitems_scroll_3_4">';
			echo '<ul class="mod_subitems_scroll  loop_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-scroll '.($subitem->getID() == $siteID ? 'current' : '').'"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date'] > 0) {
							echo ' <span class="date">(' . $subitem->getDate() ;
							// znacznik popularnosci artykulu
							if($SETTINGS['show_popularity'] == 1)
							echo '; czytany '. $subitem->getArticleCounter() . ' razy )</span>';
						}
						// ilosc podstron
						if($SETTINGS['show_subitems_counter'] > 0) {	echo ' <span class="subchild">('.$subitem->getCountChild().')</span>'; 	}
						echo '</div>';
					}
					//OPIS
					if ($SETTINGS['show_description'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
					//ZAWARTOSC STRONY
					if ($SETTINGS['show_content'] > 0) {	echo '<div class="desc">'.$subitem->getContent().'</div>';		}
					// sprawdza czy pokazac autora i zródlo
					// pokazuje jesli zaznaczono globalnie dla modulu lub indywidualnie dla itemu w opcjach widocznosci
					if( ($SETTINGS['show_author'] == 1) || ( ($SETTINGS['show_author'] == 2) && ($subitem->isShowAuthor()) ) ) {  echo '<div class="author"><b>wiecej na:&nbsp; ' . $subitem->getAuthorSource() . '</b> (' . $subitem->getAuthor() . ')</div>';	}
					if ($SETTINGS['show_date_mod'] > 0) {	echo '<div class="date_mod">' . $subitem->getDateMod() . '</div>';	}
					echo '<div class="space"></div>';
					echo '</div></a></div><div class="space"></div></li>';
					$z++;
				}
				echo '</ul>';
				echo '<div class="space"></div></div>';
				?>
				
    <script>
    $(document).ready(function() {
      $("#<? echo 'mod_'.$module['module_id']; ?>").owlCarousel({
        autoPlay: 3000,
        items : <? echo $SETTINGS['show_per_page']; ?>,
        <? if ($SETTINGS['show_per_page'] > 1) { ?>
           itemsDesktop : [1000,2], //5 items between 1000px and 901px
           itemsDesktopSmall : [720,2], // betweem 900px and 601px
           itemsTablet: [480,1], //2 items between 600 and 0
           itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
        <? } ?>
        center: false,
        autoplay:true,
	     autoplayTimeout:3000,
        loop:true,
        navigation : true,
        
      });

    });
    
    
    
     // Custom Navigation Events
$(".next").click(function(){
owl.trigger('owl.next');
})
$(".prev").click(function(){
owl.trigger('owl.prev');
})
    </script>
				


				<?
				break;
				
			case 8: // przewijanie 2/3

			echo '<div class="mod_subitems_scroll_2_3">';
			echo '<ul class="mod_subitems_scroll  loop_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-scroll '.($subitem->getID() == $siteID ? 'current' : '').'"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date'] > 0) {
							echo ' <span class="date">(' . $subitem->getDate() ;
							// znacznik popularnosci artykulu
							if($SETTINGS['show_popularity'] == 1)
							echo '; czytany '. $subitem->getArticleCounter() . ' razy )</span>';
						}
						// ilosc podstron
						if($SETTINGS['show_subitems_counter'] > 0) {	echo ' <span class="subchild">('.$subitem->getCountChild().')</span>'; 	}
						echo '</div>';
					}
					//OPIS
					if ($SETTINGS['show_description'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
					//ZAWARTOSC STRONY
					if ($SETTINGS['show_content'] > 0) {	echo '<div class="desc">'.$subitem->getContent().'</div>';		}
					// sprawdza czy pokazac autora i zródlo
					// pokazuje jesli zaznaczono globalnie dla modulu lub indywidualnie dla itemu w opcjach widocznosci
					if( ($SETTINGS['show_author'] == 1) || ( ($SETTINGS['show_author'] == 2) && ($subitem->isShowAuthor()) ) ) {  echo '<div class="author"><b>wiecej na:&nbsp; ' . $subitem->getAuthorSource() . '</b> (' . $subitem->getAuthor() . ')</div>';	}
					if ($SETTINGS['show_date_mod'] > 0) {	echo '<div class="date_mod">' . $subitem->getDateMod() . '</div>';	}
					echo '<div class="space"></div>';
					echo '</div></a></div><div class="space"></div></li>';
					$z++;
				}
				echo '</ul>';
				echo '<div class="space"></div></div>';
				?>
				
    <script>
    $(document).ready(function() {
      $("#<? echo 'mod_'.$module['module_id']; ?>").owlCarousel({
        autoPlay: 3000,
        items : <? echo $SETTINGS['show_per_page']; ?>,
        <? if ($SETTINGS['show_per_page'] > 1) { ?>
            itemsDesktop : [1000,2], //5 items between 1000px and 901px
            itemsDesktopSmall : [720,2], // betweem 900px and 601px
            itemsTablet: [480,1], //2 items between 600 and 0
            itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
        <? } ?>
        center: false,
        autoplay:true,
	     autoplayTimeout:3000,
        loop:true,
        navigation : true,
        
      });

    });
    
    
    
     // Custom Navigation Events
$(".next").click(function(){
owl.trigger('owl.next');
})
$(".prev").click(function(){
owl.trigger('owl.prev');
})
    </script>
    
    
    
    
				<?
				break;	
				
			case 9: // przewijanie 1/2 strony

		
			echo '<div class="mod_subitems_scroll_1_2">';
			echo '<ul class="mod_subitems_scroll  loop_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-scroll '.($subitem->getID() == $siteID ? 'current' : '').'"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date'] > 0) {
							echo ' <span class="date">(' . $subitem->getDate() ;
							// znacznik popularnosci artykulu
							if($SETTINGS['show_popularity'] == 1)
							echo '; czytany '. $subitem->getArticleCounter() . ' razy )</span>';
						}
						// ilosc podstron
						if($SETTINGS['show_subitems_counter'] > 0) {	echo ' <span class="subchild">('.$subitem->getCountChild().')</span>'; 	}
						echo '</div>';
					}
					//OPIS
					if ($SETTINGS['show_description'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
					//ZAWARTOSC STRONY
					if ($SETTINGS['show_content'] > 0) {	echo '<div class="desc">'.$subitem->getContent().'</div>';		}
					// sprawdza czy pokazac autora i zródlo
					// pokazuje jesli zaznaczono globalnie dla modulu lub indywidualnie dla itemu w opcjach widocznosci
					if( ($SETTINGS['show_author'] == 1) || ( ($SETTINGS['show_author'] == 2) && ($subitem->isShowAuthor()) ) ) {  echo '<div class="author"><b>wiecej na:&nbsp; ' . $subitem->getAuthorSource() . '</b> (' . $subitem->getAuthor() . ')</div>';	}
					if ($SETTINGS['show_date_mod'] > 0) {	echo '<div class="date_mod">' . $subitem->getDateMod() . '</div>';	}
					echo '<div class="space"></div>';
					echo '</div></a></div><div class="space"></div></li>';
					$z++;
				}
				echo '</ul>';
				echo '<div class="space"></div></div>';
				?>
				
    <script>
    $(document).ready(function() {
      $("#<? echo 'mod_'.$module['module_id']; ?>").owlCarousel({
        autoPlay: 3000,
        items : <? echo $SETTINGS['show_per_page']; ?>,
        <? if ($SETTINGS['show_per_page'] > 1) { ?>
            itemsDesktop : [1000,1], //5 items between 1000px and 901px
            itemsDesktopSmall : [720,1], // betweem 900px and 601px
            itemsTablet: [480,1], //2 items between 600 and 0
            itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
        <? } ?>
        center: false,
        autoplay:true,
	     autoplayTimeout:3000,
        loop:true,
        navigation : true,
        
      });

    });
    
    
    
     // Custom Navigation Events
$(".next").click(function(){
owl.trigger('owl.next');
})
$(".prev").click(function(){
owl.trigger('owl.prev');
})
    </script>
               
               
               
               
               
				<?
				break;	
				
			case 10: // przewijanie 1/4 strony

			echo '<div class="mod_subitems_scroll_1_3">';
			echo '<ul class="mod_subitems_scroll  loop_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-scroll '.($subitem->getID() == $siteID ? 'current' : '').'"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date'] > 0) {
							echo ' <span class="date">(' . $subitem->getDate() ;
							// znacznik popularnosci artykulu
							if($SETTINGS['show_popularity'] == 1)
							echo '; czytany '. $subitem->getArticleCounter() . ' razy )</span>';
						}
						// ilosc podstron
						if($SETTINGS['show_subitems_counter'] > 0) {	echo ' <span class="subchild">('.$subitem->getCountChild().')</span>'; 	}
						echo '</div>';
					}
					//OPIS
					if ($SETTINGS['show_description'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
					//ZAWARTOSC STRONY
					if ($SETTINGS['show_content'] > 0) {	echo '<div class="desc">'.$subitem->getContent().'</div>';		}
					// sprawdza czy pokazac autora i zródlo
					// pokazuje jesli zaznaczono globalnie dla modulu lub indywidualnie dla itemu w opcjach widocznosci
					if( ($SETTINGS['show_author'] == 1) || ( ($SETTINGS['show_author'] == 2) && ($subitem->isShowAuthor()) ) ) {  echo '<div class="author"><b>wiecej na:&nbsp; ' . $subitem->getAuthorSource() . '</b> (' . $subitem->getAuthor() . ')</div>';	}
					if ($SETTINGS['show_date_mod'] > 0) {	echo '<div class="date_mod">' . $subitem->getDateMod() . '</div>';	}
					echo '<div class="space"></div>';
					echo '</div></a><div class="space"></div></div><div class="space"></div></li>';
					$z++;
				}
				echo '</ul>';
				echo '</div>';

				?>
               <script>
               jQuery(document).ready(function($) {

               	$('#<? echo 'mod_'.$module['module_id']; ?>').owlCarousel({
               		center: false,
               		items:<? echo $SETTINGS['show_per_page']; ?>,
               		autoplay:true,
	                  autoplayTimeout:3000,
               		loop:true,
               		navigation : true,
               	});

               });

               </script>
				<?
				break;	
					
			case 11: // przewijanie 1/4 strony

			echo '<div class="mod_subitems_scroll_1_4">';
			echo '<ul class="mod_subitems_scroll  loop_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-scroll '.($subitem->getID() == $siteID ? 'current' : '').'"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date'] > 0) {
							echo ' <span class="date">(' . $subitem->getDate() ;
							// znacznik popularnosci artykulu
							if($SETTINGS['show_popularity'] == 1)
							echo '; czytany '. $subitem->getArticleCounter() . ' razy )</span>';
						}
						// ilosc podstron
						if($SETTINGS['show_subitems_counter'] > 0) {	echo ' <span class="subchild">('.$subitem->getCountChild().')</span>'; 	}
						echo '</div>';
					}
					//OPIS
					if ($SETTINGS['show_description'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
					//ZAWARTOSC STRONY
					if ($SETTINGS['show_content'] > 0) {	echo '<div class="desc">'.$subitem->getContent().'</div>';		}
					// sprawdza czy pokazac autora i zródlo
					// pokazuje jesli zaznaczono globalnie dla modulu lub indywidualnie dla itemu w opcjach widocznosci
					if( ($SETTINGS['show_author'] == 1) || ( ($SETTINGS['show_author'] == 2) && ($subitem->isShowAuthor()) ) ) {  echo '<div class="author"><b>wiecej na:&nbsp; ' . $subitem->getAuthorSource() . '</b> (' . $subitem->getAuthor() . ')</div>';	}
					if ($SETTINGS['show_date_mod'] > 0) {	echo '<div class="date_mod">' . $subitem->getDateMod() . '</div>';	}
					echo '<div class="space"></div>';
					echo '</div></a><div class="space"></div></div><div class="space"></div></li>';
					$z++;
				}
				echo '</ul>';
				echo '</div>';

				?>
               <script>
               jQuery(document).ready(function($) {

               	$('#<? echo 'mod_'.$module['module_id']; ?>').owlCarousel({
               		center: false,
               		items:<? echo $SETTINGS['show_per_page']; ?>,
               		autoplay:true,
	                  autoplayTimeout:3000,
               		loop:true,
               		navigation : true,
               	});

               });

               </script>
				<?
				break;	
					
				
		}
      





		if ($limit > 0) {
			$n = $this->count($Item);
			$l = count($subitems);
			echo '<div class="paging paging_'.$module['module_id'].'">';
			if ($offset >= $limit) {
				echo '<span class="previous"><a href="'. $sortArticleId .'?_subitems_offset=' . ($offset - $limit) . '" rel="nofollow">&nbsp;&lt;&nbsp;</a></span>';
			}
			// gdy ilosc itemow wieksza niz limit na strone
			if($n > $limit) {
				for ($i = 0, $j = 1; $i < $n; $i += $limit, $j++) {
					if ($i == $offset) {
						echo '<span class="current">&nbsp;' . $j . '&nbsp;</span>';
					} else {
						echo '<span><a href="'. $sortArticleId .'?_subitems_offset=' . $i . '" rel="nofollow">&nbsp;' . $j . '&nbsp;</a></span>';
					}
				}
			}
			if ($offset + $limit < $n) {
				echo '<span class="next"><a href="'. $sortArticleId .'?_subitems_offset=' . ($offset + $limit) . '" rel="nofollow">&nbsp;&gt;&nbsp;</a></span>';
			}
			echo '</div>';

		}

	}

}
