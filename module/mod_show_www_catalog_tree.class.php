<?php
define('mod_show_www_catalog_tree.class', 1);
require_once 'module/Bean.class.php';
require_once 'lib/www_catalog.php';

class mod_show_www_catalog_tree extends Mod_Bean
{
	function update($tab)
	{
		return _db_replace('mod_show_www_catalog', array('module_id'=>_db_int($tab['module_id']),'style'=>_db_int($tab['style'])));
	}

	function remove($id)
	{
		return _db_delete('mod_show_www_catalog', 'module_id='.intval($id), 1);
	}

	function validate($tab, $T)
	{
		return true;
	}

	function get($id)
	{
		return _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_show_www_catalog` WHERE module_id='.intval($id).' LIMIT 1');
	}

	function fetchGroups($GROUPS, $parent=0,$noSubgroups =  false)
	{
		$query =
		' SELECT * FROM ' . DB_PREFIX . 'www_catalog_group '.
		' WHERE parent_id=' . _db_int($parent) . ' AND active=1 '.
		' ORDER BY `order` ASC ';
		$RES = _db_get($query);
		if (!is_array($RES)) {
			return $GROUPS;
		}

		foreach ($RES as $k => $V) {
			// sprawdza czy ma szukać podgrup
			if(!$noSubgroups) {
				$GROUPS = $this->fetchGroups($GROUPS, $V['id']);
			}
			$GROUPS[] = $V;
		}

		return $GROUPS;
	}

	function diggCleanGroups($GROUPS, $parent = 0, $licznik = 0)
	{
		foreach ($GROUPS as $k => $V) {

			if ($V['parent_id'] == $parent) {
				list($licznik, $GROUPS) = $this->diggCleanGroups($GROUPS, $V['id'], $licznik);

				$sql = 'SELECT * FROM ' . DB_PREFIX . 'www_catalog wc JOIN ' . DB_PREFIX . 'www_catalog_group_in wcgi ON (wc.id=wcgi.www_catalog_id AND wcgi.www_catalog_group_id=' . _db_int($V['id']) .  ')';
				$RES = _db_get($sql);
				if (is_array($RES)) {
					$ile = count($RES);

					if ($ile == 0) {
						$GROUPS = $this->removeCleanGroups($GROUPS, $V['id']);
					}

					$licznik += $ile;
				}
			}
		}
		return array($licznik, $GROUPS);
	}

	function removeCleanGroups($GROUPS, $parent = 0)
	{
		foreach ($GROUPS as $k => $V) {
			if ($V['id'] == $parent || $V['parent_id'] == $parent) {
				unset($GROUPS[$k]);
				$GROUPS = $this->removeCleanGroups($GROUPS, $V['id']);
			}
		}
		return $GROUPS;
	}

	function cleanGroups($GROUPS)
	{
		foreach ($GROUPS as $k => $V) {
			list($licznik, $GROUPS) = $this->diggCleanGroups($GROUPS, $V['id']);
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'www_catalog wc JOIN ' . DB_PREFIX . 'www_catalog_group_in wcgi ON (wc.id=wcgi.www_catalog_id AND wcgi.www_catalog_group_id=' . _db_int($V['id']) .  ')';
			$RES = _db_get($sql);
			if (is_array($RES)) {
				$ile = count($RES);
				$licznik += $ile;
			}
			if ($licznik == 0) {
				$GROUPS = $this->removeCleanGroups($GROUPS, $V['id']);
			}
		}
		return $GROUPS;
	}

	/**
	 * Pokazuje grupy kategori.
	 * Metoda działa rekurencyjnie.
	 *
	 * @param $GROUPS
	 * @param $parent
	 * @param $first
	 * @return string
	 */
	function displayGroups($GROUPS, $parent = 0, $first = true)
	{
		$body = "";
		foreach ($GROUPS as $k => $V) {
			if ($V['parent_id'] == $parent) {
				if ($first != true) {
					$body .= ' &nbsp;/&nbsp;&nbsp; ';
				} else {
					$first = false;
				}
				if (count($this->fetchSites(array(), $GROUPS, $V['id'])) == '0') {//Performer: jesli nie ma ogloszen dajemy inny styl
				$body .= '<a class="www_catalog_group_zero" href="katalog_www/' . $V['id'] . '_'. $this->displayGroupPathUrlized($GROUPS,$V['id']) .'/">' . $V['name'] . '';
				} else {
				$body .= '<a class="www_catalog_group" href="katalog_www/' . $V['id'] . '_'. $this->displayGroupPathUrlized($GROUPS,$V['id']) .'/">' . $V['name'] . '';
				}
				// ilość ogloszeń w grupie
				$body .= '<span class="www_catalog_group_count">&nbsp;('.count($this->fetchSites(array(), $GROUPS, $V['id'])).')</span></a>';
				$this->displayGroups($GROUPS, $V['id'], false);
			}
		}

		return $body;
	}


	/**
	 * Pokazuje grupy kategorii od grupy głównej do biezacej.
	 * Metoda działa rekurencyjnie.
	 *
	 * @param $GROUPS
	 * @param $parent
	 * @param $first
	 * @return string
	 */
	function displayGroupPath($GROUPS, $searchGroup, $first = false)
	{
		$body = "";
		foreach ($GROUPS as $k => $V) {
			if($searchGroup == $V[id]) {
				if ($first != true) {
					$body .= ' / ';
				} else {
					$first = false;
					$body .= ' / ';
				}
				$body .= '<a class="www_catalog_one_group1" href="katalog_www/' . $V['id'] . '_'. $this->displayGroupPathUrlized($GROUPS,$V['id']) .'/">' . $V['name'] . '</a>';
				// ilość ogloszeń w grupie
				//$body .= '('.count($this->fetchSites(array(), $GROUPS, $V['id'])).')';

				$subBody = $this->displayGroupPath($GROUPS, $V['parent_id'], true);
				// gdy nie istnieje już grupa nadrzędna opuszcza pętle
				if(empty($subBody)) {
					// dodaje br do poczatku listy
					$body = "" . $body . "";
					break;
				}
				else {
					// dodaje grupe nadrzedna do poczatku listy
					$body = $subBody . $body;
				}
			}
		}

		return $body;
	}

   /**
     * Pokazuje scieżkę grupy kategorii od grupy głównej do biezacej.
     * Metoda działa rekurencyjnie.
     *
     * @param $GROUPS
     * @param $parent
     * @param $first
     * @return string
     */
    function displayGroupPathUrlized($GROUPS, $searchGroup, $first = false,$separator = ',')
    {
        $body = "";
        foreach ($GROUPS as $k => $V) {
            if($searchGroup == $V[id]) {
                if ($first != true) {
                    $body .= $separator;
                } else {
                    $first = false;
                }
                $body .= Site :: urlize($V['name'],false);

                $subBody = $this->displayGroupPathUrlized($GROUPS, $V['parent_id'], true);
                // gdy nie istnieje już grupa nadrzędna opuszcza pętle
                if(empty($subBody)) {
                    break;
                }
                else {
                    // dodaje grupe nadrzedna do poczatku listy
                    $body = $subBody . $body;
                }
            }
        }

        return $body;
    }

	/**
	 * Pobiera listę stron.
	 * Tylko strony aktywne.
	 *
	 * @param $NOTICES
	 * @param $GROUPS
	 * @param $parent
	 * @return unknown_type
	 */
	function fetchSites($SITES, $GROUPS, $parent )
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'www_catalog wc JOIN ' . DB_PREFIX .
		'www_catalog_group_in wcgi ON (wc.id=wcgi.www_catalog_id AND wcgi.www_catalog_group_id=' . _db_int($parent) . ') ' .
		' WHERE wc.active = 1 ';

		$RES = _db_get($sql);
		if (is_array($RES)) {
			foreach ($RES as $k => $V) {
//				// dodaje pole url_urlized
//				$V[n_title_urlized] = Site :: urlize($V[n_title]);
				$SITES[] = $V;
			}
		}

		foreach ($GROUPS as $k => $V) {
			if ($V['parent_id'] == $parent) {
				$SITES = $this->fetchSites($SITES, $GROUPS, $V['id']);
			}
		}

		return $SITES;
	}


	/**
	 * Zwraca przefiltrowaną tablicę z grupami.
	 * Sprawdza czy dla każdej z grup istnieje choć jedno ogłoszenie.
	 * Gdy nie ma żadnego, grupa jest usuwana.
	 *
	 *
	 * @param $groups
	 * @param $notices
	 * @return unknown_type
	 */
	private function filterGroups($groups,$notices) {
		foreach($groups as &$group) {
			$valid = false;
			foreach($notices as $notice) {
				if($group[id] == $notice[id]) {
					$valid = true;
					// wychodzi z pętli
					break;
				}
			}
			// sprawdza warunek do usuniecia grupy
			if(!$valid) {
				// usuwa grupę
				$group = array();
			}
		}
		return $groups;
	}

	/**
	 * Generuje w xmlu mape strony z wpisami z katalogu.
	 *
	 * @return unknown_type
	 */
	public function generateGoogleMap() {
        // pobiera liste grup
        $groupList = www_catalog_group_list_all(true);
        foreach($groupList as $group) {
        	// pobiera listę ogloszeń przypisanych do danej grupy
        	$notices = notice_get_group_notices($group[id]);
        	foreach($notices as $notice) {
        		if($notice[n_status] != 1)
        		  continue;
        		echo "<url>\r\n";
        		echo "<loc>" . MAIN_DOMAIN . "katalog_www/" . $group[id] . "_" .$this->displayGroupPathUrlized($groupList,$group[id],false) ."/". $notice[n_id] ."_" .Site :: urlize($notice['n_title'],false) . "</loc>\r\n";
        		echo "<lastmod>" . substr($notice[n_created],0,10) . "</lastmod>\r\n";
        		echo "<changefreq>daily</changefreq>";
        		echo "<priority>". $group[priority] . "</priority>\r\n";
        		echo "</url>\r\n";
        	}
        }
	}


    /**
     * Metoda generuje zewnatrzny layout.
     *
	 * @param $module
	 * @param $Item
	 * @return unknown_type
     */
	public function front($module, $Item)
	{

		$allGroupsLink = '<a class="notices_one_group1" href="katalog_www/0/0">Wszystkie grupy</a>';

		$GROUPS = array();
		$GROUPS = $this->fetchGroups($GROUPS);
// wylaczenie czyszczenie grup pustych
//		$GROUPS = $this->cleanGroups($GROUPS);

		// szczegóły ogłoszenia
		if (!empty($_GET['i_cmd']) && $_GET['i_cmd'] == 'show_site_id' && !empty($_GET['id'])) {
			$id = (int) $_GET['id'];
			$www_catalog_group_id = (int) $_GET['www_catalog_group_id'];

			$sql = 'SELECT * FROM ' . DB_PREFIX . 'www_catalog wc JOIN ' . DB_PREFIX . 'www_catalog_group_in wcgi ON (wc.id=wcgi.www_catalog_id AND wcgi.www_catalog_group_id=' . _db_int($www_catalog_group_id) .  ') WHERE wc.id=' . _db_int($id);
			$X = _db_get_one($sql);

			/***********************************/
			/** SMARTY - szczegóły ogłoszenia **/
			/***********************************/
			// dodaje scieżke dla znacznika title
            echo '<script type="text/javascript">
            jQuery(document).ready(function(){
                if(document.title)
                    document.title = "'.$this->displayGroupPathUrlized($GROUPS,$www_catalog_group_id,false," - ")." - ".Site :: urlize($X['n_title'],false).' - " + document.title;
            });
            </script>';

			$out = array();
			$out[site] = $X;
			$out[groupPath] = $allGroupsLink.$this->displayGroupPath($GROUPS,$www_catalog_group_id);
			$out[groupPathUrlized] = $this->displayGroupPathUrlized($GROUPS,$www_catalog_group_id);

			// załącza tablicę z parametrami
			$this->smarty->assign('out',$out);
			// wyświetla listę
			$this->smarty->display("mod_show_www_catalog_tree/site_detail.html");

		}
		// lista ogłoszeń - dla grupy ogłoszeń
		else if ( (!empty($_GET['i_cmd']) && $_GET['i_cmd'] == 'show_www_catalog_tree' && !empty($_GET['id']))
		      || ($_GET['i_cmd'] == 'show_site_id' && !empty($_GET['www_catalog_group_id']) ) // dodatkowy warunek dla obsługi przyjazdnych URLi
		    ) {
			$id = (int) $_GET['www_catalog_group_id'];
			$SITES = array();
			$SITES = $this->fetchSites($SITES, $GROUPS, $id);

			/***********************************/
			/** SMARTY - lista stron w katalogu www      **/
			/***********************************/
			// dodaje scieżke dla znacznika title
            echo '<script type="text/javascript">
            jQuery(document).ready(function(){
                if(document.title)
                    document.title = "'.$this->displayGroupPathUrlized($GROUPS,$id,false," - ").' - " + document.title;
            });
            </script>';

			$out = array();
			$out[sites] = $SITES;
			$out[groupPath] = $allGroupsLink.$this->displayGroupPath($GROUPS,$id);
			$out[groupPathUrlized] = $this->displayGroupPathUrlized($GROUPS,$id);

			// pobiera listę podgrup
//wylaczenie filtracji pustych grup
//			$out[subgroupList] = $this->filterGroups($this->fetchGroups(array(),$id,true) , $SITES);
			$out[subgroupList] = $this->fetchGroups(array(),$id,true);
			$out[subgroupListCnt] = count($out[subgroupList]);
			// ustawia url dla podgrup
			foreach($out[subgroupList] as &$subGroup) {
				$subGroup[groupPathUrlized] = $this->displayGroupPathUrlized($GROUPS,$subGroup[id]);
			}

			$out[currentGroup] = $id;

			// załącza tablicę z parametrami
			$this->smarty->assign('out',$out);
			// wyświetla listę
			$this->smarty->display("mod_show_www_catalog_tree/www_catalog_group_list.html");
		}
		// ogólne drzewko ogłoszeń
		else {
			foreach ($GROUPS as $k => $V) {
				if ($V['parent_id'] == 0) {
					echo '';
					echo '<a class="sites_main_group" href="katalog_www/' . $V['id'] . '_'. $this->displayGroupPathUrlized($GROUPS,$V['id']) .'/">' . $V['name'] . ' ';
					// ilość ogloszeń w grupie
					echo ' <span class="sites_main_group_count"> ('.count($this->fetchSites(array(), $GROUPS, $V['id'])).')</span> </a>';
					echo $this->displayGroups($GROUPS, $V['id']);
					echo '';
				}
			}
		}
	}
}
