<?php

require_once 'lib/www_user.php';
require_once 'lib/_gui.php';
require_once 'lib/ProvinceDAO.php';
require_once 'module/Bean.class.php';

class mod_registeruser extends Mod_Bean
{
    function update($tab)
    {
        $R = array(
      'module_id' => _db_int($tab['module_id']),
      'style' => _db_int($tab['style']),
        );
        return _db_replace('mod_registeruser', $R);
    }

    function remove($id)
    {
        return _db_delete('mod_registeruser', 'module_id='.intval($id), 1);
    }

    function validate($tab)
    {
        $ERRORS = array();
        $REQ = array('wu_login', 'wu_firstname', 'wu_lastname', 'wu_password', 'wu_password2', 'wu_email');
        foreach ($REQ as $v) {
            if (trim($tab[$v]) == '') {
                $ERRORS[] = $v;
            }
        }
        // sprawdza czy oba hasła są sobie równe
        if ($tab['wu_password'] != $tab['wu_password2']) {
            $ERRORS[] = 'pass';
        }
        // sprawdza czy login juz nie isnieje
        $r = _db_get_one('SELECT wu_id FROM ' . DB_PREFIX . 'www_user WHERE wu_login=' . _db_string($tab['wu_login']));
        if ($r != false) {
            $ERRORS[] = 'login';
        }

        if (count($ERRORS) > 0) {
            return $ERRORS;
        }
        return true;
    }

    function get($id)
    {
        $res = _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_registeruser` WHERE module_id='.intval($id).' LIMIT 1');
        return $res;
    }

    function front($module, $Item)
    {
        global $AC,$Tab,$Error;

        $ERRORS = array();
        $Tab = array();
        $Tab['menu_list'] = www_user_group_list(0);
        $registered = false;

        if (!empty($_POST['i_cmd']) && $_POST['i_cmd'] == 'register_user') {
            $USER = $_POST['USER'];
            if ($this->validate($USER) === true) {
                $USER['wu_created'] = date('Y-m-d H:i:s');
                $USER['wu_ip'] = $_SERVER['REMOTE_ADDR'];
                $USER['wu_key'] = md5(date('YmdHis') . $USER['wu_login']);
                $USER['allow_menu_access'] = $_POST['allow_menu_access'];
                $wu_id = www_user_update($USER);
                $USER['wu_id'] = $wu_id;
                www_user_update($USER, true);

                // pobiera dane usera z bazy
                $USER = www_user_get($USER['wu_id']);

                //wysylamy maila
                if (!defined('ROOT_PATH')) {
                    if (dirname(dirname(__FILE__)) . '/' == '//') { //for lame home.pl hack
                        define('ROOT_PATH', DIRECTORY_SEPARATOR);
                    } else {
                        define('ROOT_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
                    }
                }

                $mail = $this->getMailInstance();

                $mail->setSubject('Aktywacja konta');
                $mail->assignTemplateText('../_mail/register.txt');
                $mail->replace('USER', $USER);
                $url = ''. MAIN_DOMAIN .'?VERIFY='.$USER['wu_key'];
                $mail->replace('url', $url);
                $mail->add($USER['wu_email']);

                $mail->send();
                $registered = true;

                // wysyła maile ze szczegółami rejestracji do administratorów
                $this->sendRegisterUserData($USER);

            } else {
                $ERRORS = $this->validate($USER);
            }
        }

        if ($registered) {
            echo '<div class="registered"><b>Dziękujemy za rejestrację.</b><BR/> W ciągu godziny zostanie wysłany e-mail  na podany w formularzu adres z linkiem potwierdzającym.<BR/><BR/>Redakcja portalu.<BR/><BR/></div>';
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
            $out[formType] = "register_user";
            $out[user] = $USER;
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