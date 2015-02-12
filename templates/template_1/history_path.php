				<?php
				$menu = $menu_1;
				echo '<ul><li>Tutaj jesteś:&nbsp;&nbsp;</li>';
				if (is_object($menu) && is_object($Item)) {
					if (!function_exists('menu_current_item_only_recursive')) {
						function menu_current_item_only_recursive($parent,$menu,$Item) {
							$tab = $menu->getItems($parent);
							if(count($tab)>0) {
								
								foreach($tab as $id => $item) {
									echo '<li';
									if(is_object($Item) && ($id==$Item->getID() || is_object($Item->getHistory()->getByID($id)))) {
										echo ' class="current">'.$item->getLink().'&nbsp;&nbsp;/&nbsp;&nbsp;';
										menu_current_item_only_recursive($id,$menu,$Item);
									} else {
										echo ' class="normal">'.$item->getLink();
									}
									echo '</li>';
								}
								
							}
						}
					}
					
					$parent= -1;
					menu_current_item_only_recursive($parent,$menu,$Item);
				}
				
				echo '</ul>';
				// echo '<pre style="color:black;">';
				// print_r($Item);
				// echo '</pre>';
				?>
				
