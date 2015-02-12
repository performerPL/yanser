<?php
define('mod_edit_profile.class', 1);

require_once 'lib/www_user.php';
require_once 'lib/_gui.php';
require_once 'lib/ProvinceDAO.php';
require_once 'module/Bean.class.php';

class mod_edit_profile extends Mod_Bean
{
	function update($tab)
	{
		return _db_replace('mod_edit_profile', array('module_id'=>_db_int($tab['module_id']),'style'=>_db_int($tab['style'])));
	}

	function remove($id)
	{
		return _db_delete('mod_edit_profile', 'module_id='.intval($id), 1);
	}

	function validate($tab, $T)
	{
		$ERRORS = array();
		$REQ = array('wu_firstname', 'wu_lastname','wu_street',
    'wu_city', 'wu_zipcode', 'wu_email');
		foreach ($REQ as $v) {
			if (trim($tab[$v]) == '') {
				$ERRORS[] = $v;
			}
		}

		if ($tab['wu_password'] != $tab['wu_password2']) {
			$ERRORS[] = 'pass';
		}

		if (count($ERRORS) > 0) {
			return $ERRORS;
		}
		return true;
	}

	function get($id)
	{
		return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_edit_profile` WHERE module_id='.intval($id).' LIMIT 1');
	}

	function front($module, $Item)
	{
		if (!empty($_SESSION['user_www_id']) && $_SESSION['user_www_id'] > 0) {

			global $AC,$Tab,$Error;

			$ERRORS = array();
			$Tab = array();
			$Tab['menu_list'] = www_user_group_list(0);
			$registered = false;
			if (!empty($_POST['i_cmd']) && $_POST['i_cmd'] == 'update_user') {
				$USER = $_POST['USER'];
				if ($this->validate($USER) === true) {
					$USER['wu_id'] = $_SESSION['user_www_id'];
					$USER['wu_modified'] = date('Y-m-d H:i:s');
					$USER['wu_ip'] = $_SERVER['REMOTE_ADDR'];
					$USER['allow_menu_access'] = $_POST['allow_menu_access'];
					www_user_update($USER);
					www_user_update($USER, true);
					//wysylamy maila
					$registered = true;
				} else {
					$ERRORS = $this->validate($USER);
				}
			}
			if ($registered) {
				echo '<div class="registered">Konto zostało zaaktualizowane</div>';
			}
			$USER = www_user_get($_SESSION['user_www_id']);
			$GR = www_user_get_group_access($_SESSION['user_www_id']);
			$_POST['allow_menu_access'] = array();
			foreach ($GR as $V) {
				$_POST['allow_menu_access'][$V['wug_id']] = 1;
			}

			$AC = $_POST['allow_menu_access'];
			if (!is_array($AC)) {
				$AC = array();
			}

			/**************************************/
			/** SMARTY - formularz rejestracyjny **/
			/**************************************/
			$out = array();
			$out[formType] = "update_user";
			$out[user] = $USER;
			$out[user][wu_password] = "";
			$out[errors] = $ERRORS;
			// lista województw
			$out[provinceList] = ProvinceDAO :: getList();

			// załącza tablicę z parametrami
			$this->smarty->assign('out',$out);
			// wyświetla listę
			$this->smarty->display("mod_registeruser/register_form.html");
		}
	}
}
