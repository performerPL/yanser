<?php
/**
 * Klasa odpowiadająca za wysyłanie emaili.
 *
 * @package VMail
 * @version 1.0
 * @author Jakub Roszkiewicz
 */
final class VMail
{

	private
	$files = array(),
	$graphics = array(),
	$replaces = array(),
	$subject = null,
	$smarty = null,
	$target = array(),
	$body_path_txt = null,
	$body_path_html = null,

	$CONFIG = array(
		'METHOD' => null,
		'from' => null, 
		'smtp' => array(
			'host' => null,
			'port' => 25,
			'helo' => null,
			'auth' => true,
			'user' => null,
			'pass' => null,
	),
	),

	$PARAMS = array(
		'html_charset' => 'UTF-8',
		'text_charset' => 'UTF-8',
		'head_charset' => 'UTF-8',
	),

	$HEADERS = array(
		'Content-Type' => 'multipart/alternative',
		'To' => null,
		'From' => null,
		'Subject' => null,
	);

	/**
	 * Zwraca typ mime pliku
	 *
	 * @param string $f sciezka do pliku
	 * @return string typ pliku
	 */
	public function mime_content_type($f)
	{
		return trim(exec('file -bi ' . escapeshellarg($f)));
	}

	/* settery */

	/**
	 * Dodaje adresata
	 *
	 * @param string $value adres mailowy
	 * @return bool
	 */
	public function add($value)
	{
		if (!in_array($value, $this->target) && vCheck::is_email($value)) {
			$this->target[] = $value;
			return true;
		}
		return false;
	}

	public function addFile($v)
	{
		if (file_exists($v)) {
			$this->files[] = $v;
		}
	}

	/**
	 * dodaje zamiennik do szablonu
	 *
	 * @param string $what Jaki ciag zamienic w szablonie?
	 * @param string $to Na co zamienic podany ciag?
	 */
	public function replace($what, $to)
	{
		$value = $to;
		$key = (string) $what;
		$this->replaces[$key] = $value;
	}

	/**
	 * resetuje tablice zamiennikow
	 *
	 */
	public function resetReplace()
	{
		unset($this->replaces);
		$this->replaces = array();
	}
	 
	public function assignTemplateHtml($path)
	{
		$this->body_path_html = $path;
	}

	public function assignTemplateText($path)
	{
		$this->body_path_txt = $path;
	}

	public function assignTemplate($path)
	{
		$this->assignTemplateHtml($path . '.html');
		$this->assignTemplateText($path . '.txt');
	}

	/**
	 * Ustaw temat maila
	 *
	 * @param string $value temat
	 */
	public function setSubject($value)
	{
		$_subject = "=?iso-8859-2?Q?";
		$_text = str_replace(' ', '_', $value);
		$_text = urlencode(vConvert::utf2iso($_text));
		$_text = str_replace('%', '=', $_text);
		$_subject .= $_text . '?=';
		$this->subject = $_subject;
	}

	public function setConfigMethod($value)
	{
		$this->CONFIG['METHOD'] = $value;
	}

	public function setConfigFrom($value)
	{
		$this->CONFIG['from'] = $value;
	}

	public function setConfigSMTPHost($value)
	{
		$this->CONFIG['smtp']['host'] = $value;
	}

	public function setConfigSMTPPort($value)
	{
		$this->CONFIG['smtp']['port'] = (int) $value;
	}

	public function setConfigSMTPHelo($value)
	{
		$this->CONFIG['smtp']['helo'] = $value;
	}

	public function setConfigSMTPAuth($value)
	{
		$this->CONFIG['smtp']['auth'] = (boolean) $value;
	}

	public function setConfigSMTPUser($value)
	{
		$this->CONFIG['smtp']['user'] = $value;
	}

	public function setConfigSMTPPassword($value)
	{
		$this->CONFIG['smtp']['pass'] = $value;
	}

	public function setHtmlCharset($value)
	{
		$this->PARAMS['html_charset'] = $value;
	}

	public function setTextCharset($value)
	{
		$this->PARAMS['text_charset'] = $value;
	}

	public function setHeadCharset($value)
	{
		$this->PARAMS['head_charset'] = $value;
	}

	public function setHeaderContentType($value)
	{
		$this->HEADERS['Content-Type'] = $value;
	}

	public function setHeaderTo($value)
	{
		$this->HEADERS['To'] = $value;
	}

	public function setHeaderFrom($value)
	{
		$this->HEADERS['From'] = $value;
	}

	/* gettery */

	/**
	 * Ladowanie ustawien domyslnych
	 *
	 */
	public function loadDefaults()
	{
		require_once 'config/_mail.php';
		$this->setConfigMethod('smtp');
		$this->setConfigSMTPHost(MAIL_HOST);
		$this->setConfigSMTPUser(MAIL_LOGIN);
		$this->setConfigSMTPPassword(MAIL_PASSWORD);
		$this->setConfigSMTPAuth(true);
		$this->setConfigFrom(MAIL_FROM);
		$this->setHeadCharset('UTF-8');
		$this->setHtmlCharset('UTF-8');
		$this->setTextCharset('UTF-8');
		$this->setConfigSMTPPort(25);

	}

	public function __construct(& $smarty = null)
	{
		require_once 'kernel/PEAR/Mail.php'; //wczytanie funkcji PEAR'a
		require_once 'kernel/PEAR/Mail/mime.php';

		if (is_null($smarty)) {
			$this->smarty = new Smarty();
			$this->smarty->template_dir = 'templates/smarty';
			$this->smarty->compile_dir  = 'cache/template';
			$this->smarty->cache_dir    = 'cache/template';
		} else {
			$this->smarty = & $smarty;
		}

		$this->loadDefaults();
	}

	/**
	 * Wysyla zakolejkowane maile
	 *
	 */
	public function send()
	{
		$mail = new Mail_mime();
		$mail->setSubject($this->subject); //temat
		$mail->setFrom($this->CONFIG['from']);

		if (count($this->files)>0) {
			foreach ($this->files as $v) {
				$mail->addAttachment($v);
			}
		}

		if (!is_null($this->body_path_html)) {
			$body_html = '';
			reset($this->replaces);
			foreach ($this->replaces as $k => $v) {
				$this->smarty->assign($k, $v);
			}

			$body_html = $this->smarty->fetch($this->body_path_html);
//			var_dump($body_html);
			$mail->setHTMLBody($body_html); //tresc
		}
		if (!is_null($this->body_path_txt)) {
			$body_txt = '';
			reset($this->replaces);
			foreach ($this->replaces as $k => $v) {
				$this->smarty->assign($k, $v);
			}

			$body_txt = $this->smarty->fetch($this->body_path_txt);

			$mail->setTXTBody($body_txt); //tresc
		}

		if (is_array($this->graphics) && count($this->graphics) > 0) {
			foreach($this->graphics as $img) {
				$mail->addHTMLImage($img, $this->mime_content_type($img), '', true);
			}
		}

		$smtp = Mail::factory('smtp', array (
			'host' => $this->CONFIG['smtp']['host'], 
			'auth' => $this->CONFIG['smtp']['auth'],
			'username' => $this->CONFIG['smtp']['user'], 
			'password' => $this->CONFIG['smtp']['pass'],
            'port' => $this->CONFIG['smtp']['port'],
		)
		);

		$body = $mail->get($this->PARAMS);

		foreach ($this->target as $k => $v) {
			
			$headers = $mail->headers($this->HEADERS);
            $headers['To'] = $v;
            
			$s = $smtp->send($v, $headers, $body);

			//      var_dump($s);

			if (PEAR::isError($s)) {
				VLog::Write($s->getMessage());
			}
		}

		unset($mail);
	}

}
