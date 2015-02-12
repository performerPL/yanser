<?php
if (!defined('_APP')) {
	exit;
}
require_once 'lib/template.php';

class Site
{
	private
	$langs,
	$def_lang,
	$cur_lang='',
	$templates = array(),
	$def_template = 0,
	$main_template = 0,
	$conf;

	function __construct($Config)
	{
		$this->conf = $Config;
		$this->langs = array();
		if (is_array($Config->get('LANG'))) {
			foreach ($Config->get('LANG') as $code => $lang) {
				$this->langs[$code] = new Language($lang, $code);
			}
		}
		$this->def_lang = $Config->get('DEFAULT_LANG');
		if (isset($this->langs[$this->def_lang])) {
			$this->langs[$this->def_lang]->setDefault(true);
		} else {
			$this->langs[$this->def_lang] = new Language(array('LANG_NAME' => '', 'LANG_FLAG' => ''), $this->def_lang);
		}
		$this->cur_lang = $this->def_lang;

		$def_t = template_get_default();
		$this->def_template = $def_t['template_id'];
		$this->templates[$def_t['template_id']] = $def_t;

		$main_t = template_get_main();
		$this->main_template = $main_t['template_id'];
		$this->templates[$main_t['template_id']] = $main_t;
		// pobierz templata domyślnego
	}

	function getLanguages()
	{
		return $this->langs;
	}

	function getLanguage($code='')
	{
		if ($code=='' && $this->cur_lang != '') {
			$code= $this->cur_lang;
		}
		if ($code == '') {
			return $this->langs[$this->def_lang];
		} else {
			return $this->langs[$code];
		}
	}

	function setLanguage($lang)
	{
		$this->cur_lang = $lang;
	}

	function getTemplate($id=0)
	{
		if ($id > 0) {
			if (!isset($this->templates[$id])) {
				$this->templates[$id] = $this->_retrieveTemplate($id);
			}
			return $this->templates[$id]['template_dir'];
		} else {
			if ($id == 0) {
				if (!isset($this->templates[$this->def_template]) || $this->def_template == 0) {
					$tem = $this->_retrieveTemplate(0);
					$this->def_template = $tem['template_id'];
					$this->templates[$this->def_template] = $tem;
				}
				return $this->templates[$this->def_template]['template_dir'];
			} else {
				if (!isset($this->templates[$this->main_template]) || $this->main_template == 0) {
					$tem = $this->_retrieveTemplate(-1);
					$this->main_template = $tem['template_id'];
					$this->templates[$this->main_template] = $tem;
				}
				return $this->templates[$this->main_template]['template_dir'];
			}
		}
	}

	private function _retrieveTemplate($id)
	{
		if ($id==0) {
			return template_get_default();
		} else {
			if ($id > 0) {
				return template_get($id);
			} else {
				return  template_get_main();
			}
		}

	}

	public function getUrl($item, $page=0, $show=false)
	{
		$res = SITE_PATH;
		if (!USE_MOD_REWRITE) {
			$res .= INDEX_SCRIPT . '';
		}

		if ($this->def_lang != $this->cur_lang) {
			$res .= $this->cur_lang . '';
		}

		if ($item->getID() > 0) {
			if ($item->getItemType() == ITEM_LINK_OUT) {
				$res = $item->getLinkUrl();
			} else {
				$res .= intval($item->getID()) . ',';

				$res .= intval($page) . ',';
				$res .= $this->urlize($item->getUrl());
			}
		}
		return $res;
	}

	/**
	 * Zamienia string na poprawny URL.
	 *
	 * @param $str
	 * @param $ext Znacznik czy dodać rozszerzenie
	 * @return unknown_type
	 */
	public function urlize($str,$ext = true)
	{
		
		$search = array(
			".",";","!",',','"',' - ',' ','/','?',':','&','\\','ę','ó','ą','ś','ł','ż','ź','ć','ń','Ę','Ó','Ą','Ś','Ł','Ż','Ź','Ć','Ń'
			);
			$repl = array(
			'','','','','','-','-','-','','-','-','-','e','o','a','s','l','z','z','c','n','E','O','A','S','L','Z','Z','C','N'
			);
			
		$url = urlencode(str_replace($search, $repl, mb_strtolower($str,'UTF-8')));
			
		if($ext)
		  $url .= '.html';	
	
		return  $url;
	}

	public function getMetaDescription()
	{
		$tmp = $this->conf->get('META');
		return $tmp['DESCRIPTION'];
	}

	public function getMetaKeywords()
	{
		$tmp = $this->conf->get('META');
		return $tmp['KEYWORDS'];
	}

	public function getTitle()
	{
		$tmp = $this->conf->get('META');
		return $tmp['TITLE'];
	}
}
