<?php
define('mod_registernews.class', 1);

require_once 'lib/www_user.php';
require_once 'lib/_gui.php';
require_once 'module/Bean.class.php';

class mod_registernews extends Mod_Bean
{
	public function update($tab)
	{
		return _db_replace('mod_registernews', array('module_id'=>_db_int($tab['module_id']),'style'=>_db_int($tab['style'])));
	}

	public function remove($id)
	{
		return _db_delete('mod_registernews', 'module_id='.intval($id), 1);
	}


	/**
	 * Waliduje poprawność danychw  tablicy.
	 *
	 * @param $tab Tablica do walidacji.
	 * @return unknown_type
	 */
	public function validate($tab)
	{
		$ERRORS = array();
		$REQ = array('wu_firstname', 'wu_lastname','wu_email');
		foreach ($REQ as $v) {
			if (trim($tab[$v]) == '') {
				$ERRORS[] = $v;
			}
		}
			
		// sprawdza czy zgadza się kod z obrazka podany przez uzytkownika
		if(!isset($_SESSION["secretImage"]) || strtolower($_SESSION["secretImage"]) != strtolower($_REQUEST["captcha"])) {
			$ERRORS[] = 'captcha';
		}

		if (count($ERRORS) > 0) {
			return $ERRORS;
		}
		return true;
	}

	public function get($id)
	{
		return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_registernews` WHERE module_id='.intval($id).' LIMIT 1');
	}

	public function front($module, $Item)
	{
		$ERRORS = array();
		$Tab = array();
		$Tab['menu_list'] = www_user_group_list(0);
		$registered = false;

		if (!defined('ROOT_PATH')) {
			if (dirname(dirname(__FILE__)) . '/' == '//') { //for lame home.pl hack
				define('ROOT_PATH', DIRECTORY_SEPARATOR);
			} else {
				define('ROOT_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
			}
		}



		if (!empty($_POST['i_cmd']) && $_POST['i_cmd'] == 'register_news' && !empty($_POST['submit_delnews'])) {
			$USER = $_POST['USER'];
			if ( $this->validate($USER) === true) {
				// pobiera usera z bazy
				$query  = 'SELECT wu_id FROM ' . DB_PREFIX . 'www_user WHERE wu_email=' . _db_string(_db_encrypt($USER['wu_email'])) . ' AND wu_firstname=' . _db_string(_db_encrypt($USER['wu_firstname'])) . ' AND wu_lastname=' . _db_string(_db_encrypt($USER['wu_lastname'])) . ' AND wu_newsletter=1';
				$XX = _db_get_one($query);
				if(!empty($XX['wu_id'])) {
					$USER = www_user_get($XX['wu_id']);
					$USER['wu_key'] = md5(date('YmdHis') . $USER['wu_email']);

					// ustawia dane usera w sesji
					$_SESSION['newsletter_user'] = $USER;

					www_user_update($USER);
					
					$mail = $this->getMailInstance();
					$mail->assignTemplateText('../_mail/unregister.txt');
					$mail->replace('USER', $USER);
					$url = ''. MAIN_DOMAIN .'?UVERIFY='.$USER['wu_key'];
					$mail->replace('url', $url);
					$mail->add($USER['wu_email']);
						
					// ustawia temat
					$mail->setSubject("Usunięcie adresu: " . $USER['wu_email']);
					$mail->send();
					$registered = true;
				}
			}
			else {
				$ERRORS = $this->validate($USER);
			}
		}
		else if (!empty($_POST['i_cmd']) && $_POST['i_cmd'] == 'register_news') {
			$USER = $_POST['USER'];
			if (($this->validate($USER) === true) && !www_user_newsletter_exist($USER['wu_email'])) {
				$USER['wu_created'] = date('Y-m-d H:i:s');
				$USER['wu_ip'] = $_SERVER['REMOTE_ADDR'];
				$USER['wu_key'] = md5(date('YmdHis') . $USER['wu_login']);
				$USER['allow_menu_access'] = $_POST['allow_menu_access'];
				$USER['wu_newsletter'] = 1;
				$wu_id = www_user_update($USER);
				$USER['wu_id'] = $wu_id;

				// ustawia dane usera w sesji
				$_SESSION['newsletter_user'] = $USER;

				www_user_update($USER, true);
				//wysylamy maila
				
				$mail = $this->getMailInstance();
				$mail->assignTemplateText('../_mail/register.txt');
				$mail->replace('USER', $USER);
				$url = ''. MAIN_DOMAIN .'?VERIFY='.$USER['wu_key'];
				$mail->replace('url', $url);
				$mail->add($USER['wu_email']);

				// ustawia temat
				$mail->setSubject("Dodanie adresu: " . $USER['wu_email']);
				$mail->send();
				$registered = true;
				
				// wysyła maile ze szczegółami rejestracji do administratorów 
                $this->sendRegisterUserData($USER);
                
			} else {
				$ERRORS = $this->validate($USER);
			}
		}

		if ($registered) {
			if (!empty($_POST['submit_delnews'])) {
				$_SESSION[show_unregistered_mail_info] = true;
			}
			else {
				$_SESSION[show_registered_mail_info] = true;
			}
				
			// przekierowuje na główną stronę
//			_redirect(MAIN_DOMAIN);
		}
		else {
			$AC = $_POST['allow_menu_access'];
			if (!is_array($AC)) {
				$AC = array();
			}

			/**************************************/
			/** SMARTY - formularz rejestracyjny **/
			/**************************************/
			$out = array();
			$out[user] = $USER;
			$out[errors] = $ERRORS;

			global $Tab;
			$Tab['menu_list'] = www_user_group_list(0);

			// załącza tablicę z parametrami
			$this->smarty->assign('out',$out);
			// wyświetla listę
			$this->smarty->display("mod_registernews/register_form.html");

		}
	}


	public function showMessages() {
		$USER = $_SESSION['newsletter_user'];

		// dane o włascicielu konta
		$userInfo = $USER['wu_firstname'] . ' ' . $USER['wu_lastname'] . ' - ' . $USER['wu_email'];

		// komunikat o poprawniej weryfikacji
		if($_SESSION[show_registered_info]) {
			// ustawia zmienna na false
			$_SESSION[show_registered_info] = false;
			echo '<div class="registered"><h1>Newsletter</h1>
			<p>Konto <b>' . $userInfo. '</b> <br/>zostało <span class="green"><b>poprawnie zweryfikowane</b></span><br/><br/>Redakcja portalu.</p></div>';
		}
		// komunikat o usunięciu konta
		else if($_SESSION[show_unregistered_info]) {
			// ustawia zmienna na false
			$_SESSION[show_unregistered_info] = false;
			echo '<div class="registered"><h1>Newsletter</h1>Konto <b>' . $userInfo. '</b> <br/>zostało <span class="red"><b>usunięte</b></span><br/><br/>Redakcja portalu.</p></div>';
		}
		// komunikat o wysłaniu maila weryfikacyjnego przy dodaniu konta
		else if($_SESSION[show_registered_mail_info]) {
			$_SESSION[show_registered_mail_info] = false;
			echo '<div class="registered_add"><h1>Newsletter</h1>
			<p>Twój adres <b>'.$USER['wu_email'].'</b> został dodany do naszej bazy.<br/>
        W celu weryfikacji zostanie wysłany e-mail  na podany w formularzu adres z linkiem potwierdzającym.<br/><br/>Redakcja portalu. </p>
        </div>';
		}
		// komunikat o wysłaniu maila weryfikacyjnego przy dodaniu konta
		else if($_SESSION[show_unregistered_mail_info]) {
			$_SESSION[show_unregistered_mail_info] = false;
			echo '<div class="registered_del">    <h1>Newsletter</h1>
			<p>Twój adres <b>'.$USER['wu_email'].'</b> został zgłoszony do usunięcia.<br/>
        W celu weryfikacji zostanie wysłany e-mail  na podany w formularzu adres z linkiem potwierdzającym.<br/><br/>Redakcja portalu.</p> 
        </div>';
		}
	}
}
