<?php
define('mod_show_www_catalog_top.class', 1);

require_once 'module/Bean.class.php';


class mod_show_tags extends Mod_Bean
{

	/* typ modułu */
	private $moduleType = 40;

	private $tagStyle = array(
	20 => 'tag_1',
	40 => 'tag_2',
	60 => 'tag_3',
	80 => 'tag_4',
	100 => 'tag_5',
	);

	function update($tab)
	{
		return _db_replace('mod_tag',
		array(
        'module_id'=>_db_int($tab['module_id']),
        'style'=>_db_int($tab['style']),
        'limit'=>_db_int($tab['limit']),
        'show_alphabetically'=>_db_int($tab['show_alphabetically']),
        'show_popularity'=>_db_int($tab['show_popularity']),
		'show_hits'=>_db_int($tab['show_hits']),
		'search_article_id'=>_db_int($tab['search_article_id']),
		)
		);
	}

	public function remove($id)
	{
		return _db_delete('mod_tag', 'module_id='.intval($id), 1);
	}

	public function validate($tab, $T)
	{
		return true;
	}

	public function get($id)
	{
		return _db_get_one('SELECT * FROM `' . DB_PREFIX . 'mod_tag` WHERE module_id=' . intval($id) . ' LIMIT 1');
	}

	/**
	 * Czyści tagi.
	 *
	 *
	 * @param unknown_type $input_text
	 * @return unknown_type
	 */
	public function tagClean($input_text)
	{
		$input_text = strip_tags($input_text);
		$input_text = trim($input_text);
		$input_text = html_entity_decode($input_text);
		$input_text = preg_replace('/\s\s+/', ' ', $input_text);
		$input_text = str_replace('_', '-', $input_text);
		$input_text = str_replace('-', ' ', $input_text);
		$input_text = str_replace('+', ' ', $input_text);
		$input_text = str_replace('\'', ' ', $input_text);
		$input_text = str_replace('\\', '-', $input_text);
		$input_text = str_replace('/', ' ', $input_text);
		$input_text = preg_replace('|["<>!()$%?&^#:;,.*=0-9-„–]|i', '', $input_text);


		$input_text = strtolower($input_text);

		return $input_text;
	}

	public function getList($module) {
		$list = array();

		// pobiera wszystkie tagi
		$sql = " SELECT tags FROM ". DB_PREFIX ."article ".
		       " WHERE tags != '' AND tags IS NOT NULL ";
		$tags = _db_get($sql);

		foreach($tags as $row) {
			$list[] = $this->tagClean($row['tags']);
		}
		$list = implode(' ',$list);

		$list = preg_split("/ /", $list);
		$list = array_count_values($list);

		// filtruje listę tagów
		foreach($list as $tag => $hits) {
			// tylko słowa powyżej 3 liter
			if(strlen($tag) < 4) {
				unset($list[$tag]);
			}
		}


		// sortuje rosnąco
		arsort($list);

		// limit
		if($module['limit'] > 0) {
			$list = array_slice($list,0,$module['limit']);
		}

		// sortowanie alfabetyczne
		if($module['show_alphabetically'] == 1) {
			ksort($list);
		}
		else if($module['show_alphabetically'] == 2) {
			krsort($list);
		}



		return $list;
	}

	private function getTagStyle($hits) {
		foreach($this->tagStyle as $threshold => $style) {
			if($hits <= $threshold) {return $style;}// wychodzi z pętli gdy miesci się w progu
		}

		// zwraca ostani styl
		return end($this->tagStyle);
	}


	public function getModules() {
		return article_mod_list_by_type($this->moduleType);
	}


	public function front($module, $Item,$return = false)
	{
		if($return == false && file_exists($url = 'upload/common/tags_'.$module['module_id'].'.html')) {
			echo file_get_contents($url);
			return;
		}
		
		// uaktualnia dane o module (dla ustawień ręcznych)
		$module = $this->getModuleContent($module['module_id'],$module);

		// pobiera dane modułu z bazy
		$data = $this->get($module['module_id']);
		if (!$data) {
			//            return;
		}

		$tags = $this->getList($data);

		$html = "";
		foreach($tags as $tag => $hits) {
			//zmiana w htaccess - krotki adres
			$tag = '<a class="'.$this->getTagStyle($hits).'" href="tagi/'.$tag.'" title="Artykuły z portalu rolnictwo-agro.pl powiązane ze słowem kluczowym: '.$tag.'">'.$tag;
			if($data['show_hits'] == 1) {$tag .=" (".$hits.")";}
			$tag .="</a>";
			if(empty($html)) {
				$html .= $tag;
			}
			else {
				$html .= ", ".$tag;
			}
		}

		if($return) {
			return $html;
		}
		else {
			echo $html;
		}

	}
}
