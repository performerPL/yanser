<?php

define('mod_opinions.class',1);

define('OPINION_WAITING', 0);
define('OPINION_APPROVED', 1);
define('OPINION_DENIED', 2);

require_once '../module/Bean.class.php';

class mod_opinions extends Mod_Bean {

	
    /**
     * @var tablica mapująca dla kolumn
     */
    protected $colList = array(
    "default" => "waiting DESC ,last_date DESC",
    1 => array("dbCol" => "item_id"),
    2 => array("dbCol" => "item_name"),
    3 => array("dbCol" => "last_date"),
    4 => array("dbCol" => "all"),
    5 => array("dbCol" => "waiting"),
    );
	
	// scieżka względna
	protected $includePath = '../';

	function update($tab) {
		return _db_replace('mod_opinions', array(
			'module_id' => _db_int($tab['module_id']),
			'per_page' => _db_int($tab['per_page']),
			'moderation' => _db_bool($tab['moderation']),
		));
	}
	function remove($id) {
		_db_delete('mod_opinions_opinion', 'module_id=' . intval($id), 1);
		return _db_delete('mod_opinions', 'module_id=' . intval($id), 1);
	}
	function validate($tab,$T) {
		return true;
	}
	function get($id) {
		return _db_get_one('SELECT * FROM ' . DB_PREFIX . 'mod_opinions WHERE module_id = ' . intval($id));
	}
	function addOpinion($data, $opinion, $nick) {
		return _db_insert('mod_opinions_opinion', array(
			'module_id'	=> $data['module_id'],
			'opinion'	=> _db_string($opinion),
			'nick'		=> _db_string($nick),
			'ip'		=> _db_string($_SERVER['REMOTE_ADDR']),
			'status'	=> $data['moderation'] ? OPINION_WAITING : OPINION_APPROVED,
			'created'	=> 'NOW()',
			'updated'	=> 'NOW()',
		));
	}
	function getOpinion($moduleId, $opinionId) {
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'mod_opinions_opinion '
		. 'WHERE module_id = ' . intval($moduleId)
		. ' AND opinion_id = ' . intval($opinionId);
		return _db_get_one($sql);
	}
	function deleteOpinion($moduleId, $opinionId) {
		$sql = 'DELETE FROM ' . DB_PREFIX . 'mod_opinions_opinion '
		. 'WHERE module_id = ' . intval($moduleId)
		. ' AND opinion_id = ' . intval($opinionId);
		return _db_query($sql);
	}
	function updateOpinion($opinion) {
		return _db_replace('mod_opinions_opinion', array(
			'opinion_id' => $opinion['opinion_id'],
			'module_id' => $opinion['module_id'],
			'opinion' => _db_string($opinion['opinion']),
			'nick' => _db_string($opinion['nick']),
			'ip' => _db_string($opinion['ip']),
			'status' => $opinion['status'],
			'updated' => 'NOW()',
			'created' => _db_string($opinion['created']),
		));
	}
	function getAllOpinions($moduleId) {
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'mod_opinions_opinion '
		. 'WHERE module_id = ' . intval($moduleId)
		. ' ORDER BY created DESC';
		return _db_get($sql);
	}
	function getOpinions($moduleId, $offset, $limit) {
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'mod_opinions_opinion '
		. 'WHERE module_id = ' . intval($moduleId)
		. ' AND status IN (' . OPINION_APPROVED . ',' . OPINION_DENIED . ')'
		. ' ORDER BY created DESC'
		. ' LIMIT ' . $offset . ', ' . $limit;
		return _db_get($sql);
	}
	function getOpinionsNumber($moduleId) {
		$sql = 'SELECT COUNT(*) AS "count" FROM ' . DB_PREFIX . 'mod_opinions_opinion '
		. 'WHERE module_id = ' . intval($moduleId)
		. ' AND status IN (' . OPINION_APPROVED . ',' . OPINION_DENIED . ')';
		$tab = _db_get_one($sql);
		return $tab['count'];
	}
	function front($module,$Item) {
		$data = $this->get($module['module_id']);
		if(!$data)
		return;
		$style = $module['module_style'];
		echo '<a name="opinions_' . $module['module_id'] . '"></a>';
		if($module['show_module_title'])
		echo '<b>'.$module['module_name'].'</b><br />';
		$showform = true;
		if($_REQUEST['_opinion_add'] == 'x') {
			if(!isset($_SESSION["secretImage"]) || strtolower($_SESSION["secretImage"]) != strtolower($_REQUEST["captcha"])) {
				echo "<b>Musisz wpisac poprawny tekst z obrazka!</b><br />";
			} else {
				$this->addOpinion($data, $_REQUEST['_opinion'], $_REQUEST['_opinion_nick']);
				echo "Dziekujemy za dodanie opini.";
				if($data['moderation'])
				echo " Opinia ukaze sie po zatwierdzeniu przez moderatora.";
				echo "<br />";
				$showform = false;
				$_SESSION['secretImage'] = false;
			}
		}
		if($showform) {
			?>
<form method="POST" action="#opinions_<?=$module['module_id']?>"><input
	type="hidden" name="_opinion_add" value="x" /> Opinia:<br />
<textarea name="_opinion"><?=htmlspecialchars($_REQUEST['_opinion'])?></textarea><br />
Nick: <input type="text" name="_opinion_nick"
	value="<?=htmlspecialchars($_REQUEST['_opinion_nick'])?>" /><br />
<img src="secretImage.php" />Wpisz tekst z obrazka: <input type="text"
	name="captcha" /><br />
<input type="submit" value="Dodaj opinie" /></form>
			<?
		}
		$offset = $_REQUEST['_opinion_offset'];
		if(!intval($offset))
		$offset = 0;
		$limit = $data['per_page'];
		$n = $this->getOpinionsNumber($module['module_id']);
		$opinions = $this->getOpinions($module['module_id'], $offset, $limit);
		foreach($opinions as $opinion) {
			?>
<div class="opinion">
<div><span><?=htmlspecialchars($opinion['nick'])?></span> <span><?=htmlspecialchars($opinion['updated'])?></span>
</div>
<div><?=$opinion['status'] == OPINION_DENIED ? '<i>Wpis naruszal regulamin i zostal usuniety</i>' : htmlspecialchars($opinion['opinion']);?></div>
</div>
			<?
		}
		echo '<div class="paging">';
		if($offset >= $limit)
		echo '<span class="previous"><a href="?_opinion_offset=' . ($offset - $limit) . '">Poprzednie</a></span>';
		for($i = 0, $j = 1; $i < $n; $i += $limit, $j++)
		if($i == $offset)
		echo '<span class="current">&nbsp;' . $j . '&nbsp;</span>';
		else
		echo '<span><a href="?_opinion_offset=' . $i . '">&nbsp;' . $j . '&nbsp;</a></span>';
		if($offset + $limit < $n)
		echo '<span class="next"><a href="?_opinion_offset=' . ($offset + $limit) . '">Nastepne</a></span>';
		echo '</div>';
	}

	/**
	 * Zwraca listę opini wg podanych kryteriów.
	 * 
	 * @param $criteria
	 * @param $limit
	 * @param $offset
	 * @param $orderBy
	 * @return unknown_type
	 */
	
	function getOpinionModules($criteria,$limit = null,$offset = null,$orderBy = "waiting DESC ,last_date DESC")
	{
		$sql = 'SELECT i.item_name, i.item_id, m.module_id, m.module_name, '
		. '(SELECT COUNT(*) FROM cms_mod_opinions_opinion WHERE module_id = m.module_id) AS "all", '
		. '(SELECT COUNT(*) FROM cms_mod_opinions_opinion WHERE module_id = m.module_id AND status = ' . OPINION_WAITING . ') AS "waiting", '
		. '(SELECT updated FROM cms_mod_opinions_opinion WHERE module_id = m.module_id ORDER BY updated DESC LIMIT 1) AS "last_date" '
		. 'FROM cms_mod_opinions o '
		. 'JOIN cms_article_content m using (module_id) '
		. 'JOIN cms_item i using (article_id) '
		. ' HAVING 1 ';
		// dodanie kryteriów
		if(isset($criteria[activity])) {
			// nieaktywne
			if($criteria[activity] == 0) {
				$sql .= ' AND `all` = 0 ';
			}
			// aktywne 
			else if($criteria[activity] == 1) {
                $sql .= ' AND `all` = 1 ';
            }
		}
		
		$sql .= ' ORDER BY '.$orderBy;
			
		// dodanie limitu
		if(!empty($limit)) {
			$sql .= " LIMIT ". $offset . " , ". $limit;
		}

		return _db_get($sql);
	}

	function accept($moduleId, $opinionId) {
		$opinion = $this->getOpinion($moduleId, $opinionId);
		if(!$opinion)
		return false;
		$opinion['status'] = OPINION_APPROVED;
		$this->updateOpinion($opinion);
	}

	function deny($moduleId, $opinionId) {
		$opinion = $this->getOpinion($moduleId, $opinionId);
		if(!$opinion)
		return false;
		$opinion['status'] = OPINION_DENIED;
		$this->updateOpinion($opinion);
	}

	/**
	 * Wyswietla kod html ze stronnicowaniem opinii.
	 * Stronnicowanie dla listy w adminie.
	 *
	 * @param $pagingObj Obiekt od stronnicowania
	 * @param $activity Typ aktywnosci
	 * @return unknown_type
	 */    
	public function getPaging($pagingObj,$activity) {
		$out = array();
		$out[paging] = $pagingObj;
		// lista limitów wierszy na stronie
		$out[params][limitList] = array(10 => 10,20 => 20,50 => 50,100 => 100,);
        // lista statusów aktywności
        $out[params][activityList] = array(0 => "Nieaktywne",1 => "Aktywne",2 => "Wszystkie");
        $out[params][activity] = $activity;
		
		// załącza tablicę z parametrami
		$this->smarty->assign('out',$out);
		// wyświetla listę
		$this->smarty->display("mod_opinions/admin/list.html");
	}
}

