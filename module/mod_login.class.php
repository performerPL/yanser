<?php
define('mod_login.class', 1);

require_once 'lib/www_user.php';

class mod_login
{
    function update($tab)
    {
        return _db_replace('mod_login', array('module_id'=>_db_int($tab['module_id']),'style'=>_db_int($tab['style'])));
    }

    function remove($id)
    {
        return _db_delete('mod_login', 'module_id='.intval($id), 1);
    }

    function validate($tab, $T)
    {
        return true;
    }

    function get($id)
    {
        return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_login` WHERE module_id='.intval($id).' LIMIT 1');
    }

    function front($module, $Item)
    {
        global $db, $cache, $config, $phpEx, $phpbb_root_path, $template, $user;

        $result = '';
        if (!empty($_POST['i_cmd']) && $_POST['i_cmd'] == 'login_user') {
            $RES = www_user_login($_POST['login'], $_POST['password']);
            if ($RES == false) {
                $result = 'Niepoprawny login lub hasło';
                $_SESSION['error_login'] = true;
                if (!empty($_SERVER['HTTP_REFERER'])) {
                    $_SERVER['HTTP_REFERER'] = str_replace('logout', '', $_SERVER['HTTP_REFERER']);
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    echo '<script type="text/javascript">document.location.href=\'' . $_SERVER['HTTP_REFERER'] . '\'; </script>';
                    exit;
                }
            }
            // gdy logowanie przebiegło pomyślnie
            else {
                // zapisuje usera do sesji
                $_SESSION['user_www_id'] = $RES;

                // zapisuje usera do sesji forum
                $cook = $_POST['login'] ."|". md5($_POST['password']) ."|" . $cookieexptime = 108000;
                $_SESSION['minimalistBBSession']=$cook;

                if (isset($db)) {
                    unset($db);
                }
                if (isset($cache)) {
                    unset($cache);
                }
                if (isset($config)) {
                    unset($config);
                }
                if (isset($phpEx)) {
                    unset($phpEx);
                }
                if (isset($phpbb_root_path)) {
                    unset($phpbb_root_path);
                }
                if (isset($template)) {
                    unset($template);
                }
                if (isset($user)) {
                    unset($user);
                }


                if (!empty($_SERVER['HTTP_REFERER'])) {
                    $_SERVER['HTTP_REFERER'] = str_replace('logout', '', $_SERVER['HTTP_REFERER']);

                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    echo '<script type="text/javascript">document.location.href=\'' . $_SERVER['HTTP_REFERER'] . '\'; </script>';
                    exit;
                }
        }
    }

    // gdy komenda do wylogowania
    if (!empty($_GET['i_cmd']) && $_GET['i_cmd'] == 'logout' && empty($_POST['i_cmd'])) {
        // usuwa sesje usera
        unset($_SESSION['user_www_id']);
        // usuwa sesje usera z forum
        unset($_SESSION['minimalistBBSession']);

        if (!empty($_SERVER['HTTP_REFERER'])) {
            $_SERVER['HTTP_REFERER'] = str_replace('logout', '', $_SERVER['HTTP_REFERER']);

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            echo '<script type="text/javascript">document.location.href=\'' . $_SERVER['HTTP_REFERER'] . '\'; </script>';
            exit;
        }
    }

    if (empty($_SESSION['user_www_id'])) {
        if (trim($result) != '') {
            ?>
<form method="post">
<div class="form_left">&nbsp;</div>
<div class="form_right"><?php echo $result ?></div>
<div class="space"></div>
            <?php
        }
        ?>
<form method="post">
<div class="form_left">Login:</div>
<div class="form_right"><input class="input" type="text" name="login"
    value="" /></div>
<div class="space"></div>

<div class="form_left">Hasło:</div>
<div class="form_right"><input class="input" type="password"
    name="password" value="" /></div>
<div class="space"></div>

<div class="form_left"></div>
<div class="form_right"><input type="hidden" name="i_cmd" value="login_user" /><input type="submit" value="zaloguj" class="btn"></div>
<div class="space"></div>
</form>
        <?php
    } else {
        $RX = www_user_get($_SESSION['user_www_id']);
        ?> Witaj <?php echo $RX['wu_firstname'] . ' ' . $RX['wu_lastname'] ?>
(<a href="<?echo $Item->getID()?>,logout">wyloguj się</a>)

        <?php
    }
}
}
