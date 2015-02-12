<?php

define('mod_opinions.class', 1);

define('OPINION_WAITING', 0);
define('OPINION_APPROVED', 1);
define('OPINION_DENIED', 2);

class mod_opinions
{
	function update($tab)
	{
		return _db_replace('mod_opinions', array(
			'module_id' => _db_int($tab['module_id']),
			'per_page' => _db_int($tab['per_page']),
			'moderation' => _db_bool($tab['moderation']),
		));
	}

	function remove($id)
	{
		_db_delete('mod_opinions_opinion', 'module_id=' . intval($id), 1);
		return _db_delete('mod_opinions', 'module_id=' . intval($id), 1);
	}

	function validate($tab, $T)
	{
		return true;
	}

	function get($id)
	{
		return _db_get_one('SELECT * FROM ' . DB_PREFIX . 'mod_opinions WHERE module_id = ' . intval($id));
	}

	function addOpinion($data, $opinion, $nick)
	{
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

	function getOpinion($moduleId, $opinionId)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'mod_opinions_opinion '
		. 'WHERE module_id = ' . intval($moduleId)
		. ' AND opinion_id = ' . intval($opinionId);
		return _db_get_one($sql);
	}

	function deleteOpinion($moduleId, $opinionId)
	{
		$sql = 'DELETE FROM ' . DB_PREFIX . 'mod_opinions_opinion '
		. 'WHERE module_id = ' . intval($moduleId)
		. ' AND opinion_id = ' . intval($opinionId);
		return _db_query($sql);
	}

	function updateOpinion($opinion)
	{
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

	function getAllOpinions($moduleId)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'mod_opinions_opinion '
		. 'WHERE module_id = ' . intval($moduleId)
		. ' ORDER BY created DESC';
		return _db_get($sql);
	}

	function getOpinions($moduleId, $offset, $limit)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'mod_opinions_opinion '
		. 'WHERE module_id = ' . intval($moduleId)
		. ' AND status IN (' . OPINION_APPROVED . ',' . OPINION_DENIED . ')'
		. ' ORDER BY created DESC';
		// gdy limit > 0
		if($limit > 0)
			$sql .= ' LIMIT ' . $offset . ', ' . $limit;
		return _db_get($sql);
	}

	function getOpinionsNumber($moduleId)
	{
		$sql = 'SELECT COUNT(*) AS "count" FROM ' . DB_PREFIX . 'mod_opinions_opinion '
		. 'WHERE module_id = ' . intval($moduleId)
		. ' AND status IN (' . OPINION_APPROVED . ',' . OPINION_DENIED . ')';
		$tab = _db_get_one($sql);
		return $tab['count'];
	}

	function front($module, $Item)
	{
		$data = $this->get($module['module_id']);
		if (!$data) {
			return;
		}
		$style = $module['module_style'];
		echo '<a name="opinions_' . $module['module_id'] . '"></a>';
		if ($module['show_module_title']) {
			echo '<b>'.$module['module_name'].'</b><br />';
		}
		$showform = true;
		if ($_REQUEST['_opinion_add'] == 'x') {
			if(!isset($_SESSION["secretImage"]) || strtolower($_SESSION["secretImage"]) != strtolower($_REQUEST["captcha"])) {
				$info = '<p class="opinion_info">Musisz wpisac poprawny tekst z obrazka!</p>';
				$display_form = '1';
			} else {
				$this->addOpinion($data, $_REQUEST['_opinion'], $_REQUEST['_opinion_nick']);
				$info = '<p class="opinion_info">Dziekujemy za dodanie opini.</p>';
				if ($data['moderation']) {
					$info = '<p class="opinion_info">Opinia ukaze sie po zatwierdzeniu przez moderatora.</p>';
				}
				$showform = false;
				$_SESSION['secretImage'] = false;
			}
		}

		$n = $this->getOpinionsNumber($module['module_id']);

		echo '<section class="mod_opinions mod_opinions_'.$module['module_id'].'" id="mod_opinions_'.$module['module_id'].'"><div class="margin"><div class="inside">';
		echo '<div class="header"><p class="opinion_form_title">Opinie/komentarze <span>('.$n.')</span></p>    <a href="javascript:Pokaz(\'opinion_form\')" class="btn">Moja opinia</a></div>';
		echo $info;
		//if ($showform) {
		?>



<form method="POST" action=""><input type="hidden" name="_opinion_add" 	value="x" />

<div class="opinion_form" id="opinion_form" <? echo ($display_form==1 ? ' ' : ' style="display: none;" ');?>>

      <div class="opinion_form_box">
         <div class="opinion_form_text">Opinia/komentarz:</div>
         <div class="opinion_form_text_input"><textarea name="_opinion"><?=htmlspecialchars($_REQUEST['_opinion'])?></textarea></div>
         <div class="space"></div>
      </div>
      <div class="opinion_form_box">
         <div class="opinion_form_nick">Twój podpis:</div>
         <div class="opinion_form_nick_input"><input type="text"	name="_opinion_nick"	value="<?=htmlspecialchars($_REQUEST['_opinion_nick'])?>" /></div>
         <div class="space"></div>
      </div>
      <div class="opinion_form_box">
         <div class="opinion_form_key_name">AntySPAM:</div>
         <div class="opinion_form_key_image" style="width: 145px;"><img	src="secretImage.php" /></div>
         <div class="space"></div>
      </div>
      <div class="opinion_form_box">
         <div class="opinion_form_key_name">Wpisz tekst z obrazka:</div>
         <div class="opinion_form_key_input"><input type="text" name="captcha" /></div>
         <div class="space"></div>
      </div>
      <div class="opinion_form_box">
         <div class="opinion_form_key_name">.</div>
         <div class="opinion_form_key_input"><input type="submit"	 class="btn" value="Zapisz" /><div class="space"></div></div>
         <div class="space"></div>
      </div>
<div class="space"></div>
</div>
</form>


<?php
//}
$offset = $_REQUEST['_opinion_offset'];
if (!intval($offset)) {
	$offset = 0;
}
$limit = $data['per_page'];
$n = $this->getOpinionsNumber($module['module_id']);
$opinions = $this->getOpinions($module['module_id'], $offset, $limit);
foreach($opinions as $opinion) {
	?>
<div class="opinion">
<div class="opinion_head"><span class="nick"><?=htmlspecialchars($opinion['nick'])?></span><br />
<span class="date"><?=htmlspecialchars($opinion['updated'])?></span></div>
<div class="opinion_text"><?=$opinion['status'] == OPINION_DENIED ? '<i>Wpis naruszal regulamin i zostal usuniety</i>' : htmlspecialchars($opinion['opinion']);?></div>
<div class="space"></div>
</div>

	<?php
}
// stronnicowanie tylko gdy limit > 0
if($limit > 0) {
	echo '<div class="paging">';
	if ($n > $limit) {
		if ($offset >= $limit) {
			echo '<span class="previous"><a href="'. $Item->getID() .'?_opinion_offset=' . ($offset - $limit) . '#opinie">Poprzednie</a></span>';
		}

		for ($i = 0, $j = 1; $i < $n; $i += $limit, $j++) {

			if ($i == $offset) {
				echo '<span class="current">&nbsp;' . $j . '&nbsp;</span>';
			} else {
				echo '<span><a href="'. $Item->getID() .'?_opinion_offset=' . $i . '#opinie">&nbsp;' . $j . '&nbsp;</a></span>';
			}
		}
	}
	if ($offset + $limit < $n) {
		echo '<span class="next"><a href="'. $Item->getID() .'?_opinion_offset=' . ($offset + $limit) . '#opinie">Nastepne</a></span>';
	}
	echo '</div>';

}

echo '<aside class="alert">Redakcja nie ponosi odpowiedzialności za treść opinii. Zastrzegamy sobie prawo do usuwania tresci zabronionych przez prawo, wulgarnych lub obraźliwych.</aside>';
echo '</div></div></section>';


	}

	function getOpinionModules()
	{
		$sql = 'SELECT i.item_name, m.module_id, m.module_name, '
		. '(SELECT COUNT(*) FROM cms_mod_opinions_opinion WHERE module_id = m.module_id) AS "all", '
		. '(SELECT COUNT(*) FROM cms_mod_opinions_opinion WHERE module_id = m.module_id AND status = ' . OPINION_WAITING . ') AS "waiting" '
		. 'FROM cms_mod_opinions o '
		. 'JOIN cms_article_content m using (module_id) '
		. 'JOIN cms_item i using (article_id) '
		. 'ORDER BY item_name';
		return _db_get($sql);
	}

	function accept($moduleId, $opinionId)
	{
		$opinion = $this->getOpinion($moduleId, $opinionId);
		if (!$opinion) {
			return false;
		}
		$opinion['status'] = OPINION_APPROVED;
		$this->updateOpinion($opinion);
	}

	function deny($moduleId, $opinionId)
	{
		$opinion = $this->getOpinion($moduleId, $opinionId);
		if (!$opinion) {
			return false;
		}
		$opinion['status'] = OPINION_DENIED;
		$this->updateOpinion($opinion);
	}

}

