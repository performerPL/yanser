<?php
define('mod_shownotices_tree.class', 1);
require_once 'module/Bean.class.php';
require_once 'module/mod_addnotice.class.php';

class mod_shownotices_tree extends Mod_Bean
{
    function update($tab)
    {
        return _db_replace('mod_shownotices', array('module_id'=>_db_int($tab['module_id']),'style'=>_db_int($tab['style'])));
    }

    function remove($id)
    {
        return _db_delete('mod_shownotices', 'module_id='.intval($id), 1);
    }

    function validate($tab, $T)
    {
        return true;
    }

    function get($id)
    {
        return _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_shownotices` WHERE module_id='.intval($id).' LIMIT 1');
    }

    function fetchGroups($GROUPS, $parent=0,$noSubgroups =  false)
    {
        $query =
        ' SELECT * FROM ' . DB_PREFIX . 'notice_group '.
        ' WHERE ng_parent_id=' . _db_int($parent) .
        '  AND ng_active=1 ' .
        ' ORDER BY ng_order ASC';

        $RES = _db_get($query);
        if (!is_array($RES)) {
            return $GROUPS;
        }

        foreach ($RES as $k => $V) {
            // sprawdza czy ma szukać podgrup
            if(!$noSubgroups) {
                $GROUPS = $this->fetchGroups($GROUPS, $V['ng_id']);
            }
            $GROUPS[] = $V;
        }

        return $GROUPS;
    }

    function diggCleanGroups($GROUPS, $parent = 0, $licznik = 0)
    {
        foreach ($GROUPS as $k => $V) {

            if ($V['ng_parent_id'] == $parent) {
                list($licznik, $GROUPS) = $this->diggCleanGroups($GROUPS, $V['ng_id'], $licznik);

                $sql = 'SELECT * FROM ' . DB_PREFIX . 'notice n JOIN ' . DB_PREFIX . 'notice_group_in ngi ON (n.n_id=ngi.n_id AND ngi.ng_id=' . _db_int($V['ng_id']) .  ')';
                $RES = _db_get($sql);
                if (is_array($RES)) {
                    $ile = count($RES);

                    if ($ile == 0) {
                        $GROUPS = $this->removeCleanGroups($GROUPS, $V['ng_id']);
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
            if ($V['ng_id'] == $parent || $V['ng_parent_id'] == $parent) {
                unset($GROUPS[$k]);
                $GROUPS = $this->removeCleanGroups($GROUPS, $V['ng_id']);
            }
        }
        return $GROUPS;
    }

    function cleanGroups($GROUPS)
    {
        foreach ($GROUPS as $k => $V) {
            list($licznik, $GROUPS) = $this->diggCleanGroups($GROUPS, $V['ng_id']);
            $sql = 'SELECT * FROM ' . DB_PREFIX . 'notice n JOIN ' . DB_PREFIX . 'notice_group_in ngi ON (n.n_id=ngi.n_id AND ngi.ng_id=' . _db_int($V['ng_id']) .  ')';
            $RES = _db_get($sql);
            if (is_array($RES)) {
                $ile = count($RES);
                $licznik += $ile;
            }
            if ($licznik == 0) {
                $GROUPS = $this->removeCleanGroups($GROUPS, $V['ng_id']);
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
            if ($V['ng_parent_id'] == $parent) {
                if ($first != true) {
                    $body .= ' &nbsp;/&nbsp;&nbsp; ';
                } else {
                    $first = false;
                }
                if (count($this->fetchNotices(array(), $GROUPS, $V['ng_id'])) == '0') {//Performer: jesli nie ma ogloszen dajemy inny styl
                    $body .= '<a class="notices_group_zero" href="ogloszenia/' . $V['ng_id'] . '_'. $this->displayGroupPathUrlized($GROUPS,$V['ng_id']) .'/" title="Ogłoszenie: ' . $V['ng_name'] . '">' . $V['ng_name'] . '';
                } else {
                    $body .= '<a class="notices_group" href="ogloszenia/' . $V['ng_id'] . '_'. $this->displayGroupPathUrlized($GROUPS,$V['ng_id']) .'/"  title="Ogłoszenia: ' . $V['ng_name'] . '">' . $V['ng_name'] . '';
                }
                // ilość ogloszeń w grupie
                $body .= '<span class="notices_main_group_count">&nbsp;('.count($this->fetchNotices(array(), $GROUPS, $V['ng_id'])).')</span></a>';
                $this->displayGroups($GROUPS, $V['ng_id'], false);
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
            if($searchGroup == $V[ng_id]) {
                if ($first != true) {
                    $body .= ' / ';
                } else {
                    $first = false;
                    $body .= ' / ';
                }
                $body .= '<a class="notices_one_group1" href="ogloszenia/' . $V['ng_id'] . '_'. $this->displayGroupPathUrlized($GROUPS,$V['ng_id']) .'/"  title="Ogłoszenia: ' . $V['ng_name'] . '">' . $V['ng_name'] . '</a>';
                // ilość ogloszeń w grupie
                //$body .= '('.count($this->fetchNotices(array(), $GROUPS, $V['ng_id'])).')';

                $subBody = $this->displayGroupPath($GROUPS, $V['ng_parent_id'], true);
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
            if($searchGroup == $V[ng_id]) {
                if ($first != true) {
                    $body .= $separator;
                } else {
                    $first = false;
                }
                $body .= Site :: urlize($V['ng_name'],false);

                $subBody = $this->displayGroupPathUrlized($GROUPS, $V['ng_parent_id'], true);
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
     * Pobiera listę ogłoszeń.
     * Tylko ogloszenia aktywne.
     *
     * @param $NOTICES
     * @param $GROUPS
     * @param $parent
     * @param $mainGroup
     * @return unknown_type
     */
    function fetchNotices($NOTICES, $GROUPS, $parent , $mainGroup = null)
    {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'notice n JOIN ' . DB_PREFIX .
		'notice_group_in ngi ON (n.n_id=ngi.n_id AND ngi.ng_id=' . _db_int($parent) .  ') JOIN ' . DB_PREFIX . 'notice_group_main ngm ON (ngi.ngm_id=ngm.ngm_id)' .
		" WHERE n.n_status = 1 ";

        // warunek dla grupy głównej
        $maGroup = _db_int($mainGroup);
        if(!empty($mainGroup))
        $sql .= " AND ngi.ngm_id = ".$mainGroup;

        $RES = _db_get($sql);
        if (is_array($RES)) {
            foreach ($RES as $k => $V) {
                // dodaje pole n_title_urlized
                $V[n_title_urlized] = Site :: urlize($V[n_title]);
                $NOTICES[] = $V;
            }
        }

        foreach ($GROUPS as $k => $V) {
            if ($V['ng_parent_id'] == $parent) {
                $NOTICES = $this->fetchNotices($NOTICES, $GROUPS, $V['ng_id'],$mainGroup);
            }
        }

        return $NOTICES;
    }

    /**
     * Zwraca tablice z grupami głównymi.
     * Jako element o indeksie 0 daje "Wszystkie", jest to element neutralny dla filtracji.
     *
     *
     * @return array
     */
    private function getMainGroups()
    {
        $mod_addnotice = new mod_addnotice();
        $mainGroups = $mod_addnotice->fetchMainGroups(false);
        return array_merge(array(0 => "Wszystkie"),$mainGroups);
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
                if($group[ng_id] == $notice[ng_id]) {
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
     * Generuje w xmlu mape strony z ogloseniami.
     *
     * @return unknown_type
     */
    public function generateGoogleMap() {
        // pobiera liste grup
        $groupList = notice_group_list_all(true);
        foreach($groupList as $group) {
            // pobiera listę ogloszeń przypisanych do danej grupy
            $notices = notice_get_group_notices($group[ng_id]);
            foreach($notices as $notice) {
                if($notice[n_status] != 1)
                continue;
                echo "<url>\r\n";
                echo "<loc>" . MAIN_DOMAIN . "ogloszenia/" . $group[ng_id] . "_" .$this->displayGroupPathUrlized($groupList,$group[ng_id],false) ."/". $notice[n_id] ."_" .Site :: urlize($notice['n_title'],false) . "</loc>\r\n";
                echo "<lastmod>" . substr($notice[n_created],0,10) . "</lastmod>\r\n";
                echo "<changefreq>daily</changefreq>";
                echo "<priority>". $group[ng_priority] . "</priority>\r\n";
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

        $allGroupsLink = '<a class="notices_one_group1" href="ogloszenia/0/0" Title="Ogłoszenia w porlatu rolnictwo-agro.pl">Wszystkie grupy</a>';

        $GROUPS = array();
        $GROUPS = $this->fetchGroups($GROUPS);
        // wylaczenie czyszczenie grup pustych
        //		$GROUPS = $this->cleanGroups($GROUPS);

        // szczegóły ogłoszenia
        if (!empty($_GET['i_cmd']) && $_GET['i_cmd'] == 'show_notice_id' && !empty($_GET['ng_id'])&& !empty($_GET['n_id'])) {
            $ng_id = (int) $_GET['ng_id'];
            $n_id = (int) $_GET['n_id'];

            $sql = 'SELECT * FROM ' . DB_PREFIX . 'notice n JOIN ' . DB_PREFIX . 'notice_group_in ngi ON (n.n_id=ngi.n_id AND ngi.ng_id=' . _db_int($ng_id) .  ') JOIN ' . DB_PREFIX . 'notice_group_main ngm ON (ngi.ngm_id=ngm.ngm_id) WHERE n.n_id=' . _db_int($n_id);
            $X = _db_get_one($sql);

            /***********************************/
            /** SMARTY - szczegóły ogłoszenia **/
            /***********************************/
            // dodaje scieżke dla znacznika title
            echo '<script type="text/javascript">
            jQuery(document).ready(function(){
                if(document.title)
                    document.title = "'.$this->displayGroupPathUrlized($GROUPS,$ng_id,false," - ")." - ".Site :: urlize($X['n_title'],false).' - " + document.title;
            });
            </script>';

            $out = array();
            $out[notice] = $X;
            $out[groupPath] = $allGroupsLink.$this->displayGroupPath($GROUPS,$ng_id);
            $out[groupPathUrlized] = $this->displayGroupPathUrlized($GROUPS,$ng_id);

            // załącza tablicę z parametrami
            $this->smarty->assign('out',$out);
            // wyświetla listę
            $this->smarty->display("mod_shownotices_tree/notice_detail.html");

        }
        // lista ogłoszeń - dla grupy ogłoszeń
        else if ( (!empty($_GET['i_cmd']) && $_GET['i_cmd'] == 'show_n_tree' && !empty($_GET['ng_id']))
        || ($_GET['i_cmd'] == 'show_notice_id' && !empty($_GET['ng_id']) ) // dodatkowy warunek dla obsługi przyjazdnych URLi
        ) {
            $ng_id = (int) $_GET['ng_id'];
            $NOTICES = array();
            $NOTICES = $this->fetchNotices($NOTICES, $GROUPS, $ng_id,$_REQUEST[mainGroup]);

            /***********************************/
            /** SMARTY - lista ogłoszeń       **/
            /***********************************/
            // dodaje scieżke dla znacznika title
            echo '<script type="text/javascript">
            jQuery(document).ready(function(){
                if(document.title)
                    document.title = "'.$this->displayGroupPathUrlized($GROUPS,$ng_id,false," - ").' - " + document.title;
            });
            </script>';

            $out = array();
            $out[notices] = $NOTICES;
            $out[groupPath] = $allGroupsLink.$this->displayGroupPath($GROUPS,$ng_id);
            $out[groupPathUrlized] = $this->displayGroupPathUrlized($GROUPS,$ng_id);

            // pobiera listę podgrup
            //wylaczenie filtracji pustych grup
            //			$out[subgroupList] = $this->filterGroups($this->fetchGroups(array(),$ng_id,true) , $NOTICES);
            $out[subgroupList] = $this->fetchGroups(array(),$ng_id,true);
            $out[subgroupListCnt] = count($out[subgroupList]);
            // ustawia url dla podgrup
            foreach($out[subgroupList] as &$subGroup) {
                $subGroup[groupPathUrlized] = $this->displayGroupPathUrlized($GROUPS,$subGroup[ng_id]);
            }

            $out[currentGroup] = $ng_id;
            // grupy główne
            $out[mainGroups] = $this->getMainGroups();
            $out[selectedMainGroup] = $_REQUEST[mainGroup];

            // załącza tablicę z parametrami
            $this->smarty->assign('out',$out);
            // wyświetla listę
            $this->smarty->display("mod_shownotices_tree/notices_group_list.html");
        }
        // ogólne drzewko ogłoszeń
        else {
            foreach ($GROUPS as $k => $V) {
                if ($V['ng_parent_id'] == 0) {
                    echo '';
                    echo '<a class="notices_main_group" href="ogloszenia/' . $V['ng_id'] . '_'. $this->displayGroupPathUrlized($GROUPS,$V['ng_id']) .'/" title="Ogłoszenia: ' . $V['ng_name'] . '">' . $V['ng_name'] . ' ';
                    // ilość ogloszeń w grupie
                    echo ' <span class="notices_main_group_count"> ('.count($this->fetchNotices(array(), $GROUPS, $V['ng_id'])).')</span> </a>';
                    echo $this->displayGroups($GROUPS, $V['ng_id']);
                    echo '';
                }
            }
        }
    }
}
