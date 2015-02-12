<?php
require_once 'lib/promotion.php';

require_once 'module/Bean.class.php';

class mod_showgroups extends Mod_Bean
{
	function update($tab)
	{
		$R = array(
        'module_id' => _db_int($tab['module_id']),
        'style' => _db_int($tab['style']),
        'grupa' => _db_int($tab['grupa']),
        'wyniki' => _db_int($tab['wyniki']),
        'strony' => _db_int($tab['strony']),
        'show_title' => _db_int($tab['show_title']),
        'show_date' => _db_int($tab['show_date']),
        'show_date_mod' => _db_int($tab['show_date_mod']),
        'show_zajawka' => _db_int($tab['show_zajawka']),
        'show_icon' => _db_int($tab['show_icon']),
        'pokazuj' => _db_int($tab['pokazuj']),
				//'show_author'=>_db_int($tab['show_author']),
		);
		return _db_replace('mod_showgroups', $R);
	}

	function remove($id)
	{
		return _db_delete('mod_showgroups', 'module_id='.intval($id), 1);
	}

	function validate($tab, $T)
	{
		return true;
	}

	function get($id)
	{
		$res = _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_showgroups` WHERE module_id='.intval($id).' LIMIT 1');
		$GRUPY = array();
		$PR = promotion_list_item();
		foreach ($PR as $k => $V) {
			$GRUPY[$V['promotion_id']] = $V['name'];
		}
		$res['GRUPY'] = $GRUPY;
		return $res;
	}

	public function count($Item, $SETTINGS)
	{
		return count(item_get_orders_showgroup(0, 0, $SETTINGS));
	}

	function front($module, $Item)
	{
			
		// uaktualnia dane o module (dla ustawien recznych)
		$module = $this->getModuleContent($module['module_id'],$module);

		global $GL_CONF;
		$cfg = $GL_CONF['IMAGES_FILES'];
		//    $subitems = new Menu('',$Item->getID(),1);
		//    $subitems->printList();
		$options = $this->get($module['module_id']);
		$SETTINGS = $this->get($module['module_id']);

		// sprawdza czy sortowanie ma sie odbywac przez inny artykul
		if(!empty($module['sort_article_id'])) {
			$sortArticleId = $module['sort_article_id'];
		}
		else {
			$sortArticleId = $Item->getID();
		}

		$subitems = item_get_orders_showgroup(0, 0, $SETTINGS);

		$style = $module['module_style'];
      
      //Zmiany stronnicowania 2014-05-20 - nowa wersja z przewijaniem
		//$limit = $SETTINGS['strony'];
      $limit = 0;
      
		$offset = _db_int($_REQUEST['_showgroups_offset']);
		if (!$offset) {
			$offset = 0;
		}

		if ($limit > 0) {
			$subitems = array_slice($subitems, $offset, $limit);
		}

		// tablica z id modulów ktore maja miec znacznik h2
		$h1Modules = array(253,252);
		// sprawdza czy strona glówna, jesli tak znacznik tytulu to h2, w przeciwnym przypadku div
		$showTitleTag = (in_array($module['module_id'],$h1Modules)) ? "h2" : "div";
		
      echo '<div class="width_site width_'.$data['module_id'].'"><div class="inside_content">';
		switch ($style) {

				
	case 0: // 
				echo '<ul class="mod_subitems mod_subitems_0  mod_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-1-1"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
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
					if ($SETTINGS['show_zajawka'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
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
				break;
				
			case 1: // 2 kolumny
				echo '<ul class="mod_subitems mod_subitems_1  mod_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-1-2"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
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
					if ($SETTINGS['show_zajawka'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
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
				break;

				case 2: // 1-3
				echo '<ul class="mod_subitems mod_subitems_3  mod_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-1-3"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'"><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt="'.strip_tags($subitem->getName()).'" /></div>';
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
					if ($SETTINGS['show_zajawka'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
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
				break;

			case 3: // 4 kolumny
				echo '<ul class="mod_subitems mod_subitems_4  mod_'.$module['module_id'].' loop" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-1-4"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
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
					if ($SETTINGS['show_zajawka'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
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
				break;


			case 4: // 6 kolumn
				echo '<ul class="mod_subitems mod_subitems_4  mod_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-1-6"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'"><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt="'.strip_tags($subitem->getName()).'" /></div>';
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
					if ($SETTINGS['show_zajawka'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
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
				break;

			case 5: // 8 kolumn
				echo '<ul class="mod_subitems mod_subitems_5  mod_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-1-8"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
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
					if ($SETTINGS['show_zajawka'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
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
				break;

			case 6: // przewijanie cała strona
			echo '<ul class="mod_subitems_scroll  loop_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-scroll"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
					//IKONA 
					if ($subitem->getIcon() != '' && $SETTINGS['show_icon'] > 0) {
						echo '<div class="icon"><img src="' . $cfg['IMAGE_BASE_URL'] . $subitem->getIcon() .'" alt ="'.strip_tags($subitem->getName()).'"  /></div>';
					}
					//TYTUL
					if ($SETTINGS['show_title'] > 0) {
						echo '<div class="title">'.$subitem->getName();
						if ($SETTINGS['show_date_mod'] > 0) {
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
					if ($SETTINGS['show_zajawka'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
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
				?>
               <script>
               jQuery(document).ready(function($) {

               	$('#<? echo 'mod_'.$module['module_id']; ?>').owlCarousel({
               	   autoplay:true,
	                  autoplayTimeout:2000,
               		center: false,
               		items:<? echo $SETTINGS['strony']; ?>,
               		loop:true,
               		nav:true,
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
					echo '<li class="subitems_li_'.$z.' box box-scroll"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
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
					if ($SETTINGS['show_zajawka'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
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
               		items:<? echo $SETTINGS['strony']; ?>,
               		autoplay:true,
	                  autoplayTimeout:3000,
               		loop:true,
               		nav:true,
               	});

               });

               </script>
				<?
				break;	
				
			case 8: // przewijanie cała strona
			echo '<div class="mod_subitems_scroll_2_3">';
			echo '<ul class="mod_subitems_scroll  loop_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-scroll"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
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
					if ($SETTINGS['show_zajawka'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
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
               		items:<? echo $SETTINGS['strony']; ?>,
               		autoplay:true,
	                  autoplayTimeout:3000,
               		loop:true,
               		nav:true,
               	});

               });

               </script>
				<?
				break;	
				
			case 9: // przewijanie cała strona
			echo '<div class="mod_subitems_scroll_1_2">';
			echo '<ul class="mod_subitems_scroll  loop_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-scroll"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
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
					if ($SETTINGS['show_zajawka'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
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
               		items:<? echo $SETTINGS['strony']; ?>,
               		autoplay:true,
	                  autoplayTimeout:3000,
               		loop:true,
               		nav:true,
               	});

               });

               </script>
				<?
				break;	
						
				
			case 10: // przewijanie cała strona
			echo '<div class="mod_subitems_scroll_1_3">';
			echo '<ul class="mod_subitems_scroll  loop_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-scroll"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
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
					if ($SETTINGS['show_zajawka'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
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
               		items:<? echo $SETTINGS['strony']; ?>,
               		autoplay:true,
	                  autoplayTimeout:3000,
               		loop:true,
               		nav:true,
               	});

               });

               </script>
				<?
				break;	
						
				
				
				
				
			case 11: // przewijanie cała strona
			echo '<div class="mod_subitems_scroll_1_4">';
			echo '<ul class="mod_subitems_scroll  loop_'.$module['module_id'].'" id="mod_'.$module['module_id'].'">';
				$z = 0;
				foreach ($subitems as $subitem_id) {
					$subitem = new Item($subitem_id['item_id']);
					if (!$subitem->isActive()) {	continue; }
					echo '<li class="subitems_li_'.$z.' box box-scroll"><div class="margin"><a href=' . $subitem->getLinkUrl() . ' title="'.strip_tags($subitem->getName()).'" ><div class="inside">';
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
					if ($SETTINGS['show_zajawka'] > 0) {	echo '<div class="desc">'.$subitem->getDescription().'</div>';		}
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
               		items:<? echo $SETTINGS['strony']; ?>,
               		autoplay:true,
	                  autoplayTimeout:3000,
               		loop:true,
               		nav:true,
               	});

               });

               </script>
				<?
				break;	
							
		
		}
   echo '</div></div>';


		$n = $this->count($Item, $SETTINGS);
		if ( ($limit > 0) && ($n > 1) ) {
			$l = count($subitems);
			echo '<div class="paging">';
			if ($offset >= $limit) {
				echo '<span class="previous"><a href="'.$sortArticleId.'?_showgroups_offset=' . ($offset - $limit) . '"  rel="nofollow">Poprzednie</a></span>';
			}
			for ($i = 0, $j = 1; $i < $n; $i += $limit, $j++) {
				if ($i == $offset) {
					echo '<span class="current">&nbsp;' . $j . '&nbsp;</span>';
				} else {
					echo '<span><a href="'.$sortArticleId.'?_showgroups_offset=' . $i . '"  rel="nofollow">&nbsp;' . $j . '&nbsp;</a></span>';
				}
			}
			if ($offset + $limit < $n) {
				echo '<span class="next"><a href="'.$sortArticleId.'?_showgroups_offset=' . ($offset + $limit) . '"  rel="nofollow">Nastepne</a></span>';
			}
			echo '</div>';

		}
	}
}