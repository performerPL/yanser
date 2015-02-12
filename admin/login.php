<?php
require_once '_header_unlogged.php';
require_once '../lib/config.php';
require_once '../lib/config_value.php';
$GL_CONF = config_value_tree();
require '../lib/user.php';

if (substr($_SERVER['HTTP_HOST'], 0, 3) != 'www' && $_SERVER['HTTP_HOST'] != "localhost") {
  header('Location: http://www.'.$_SERVER['HTTP_HOST'] . '/admin/');
}

if (_sec_logged()) {
  _redirect('index.php');
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'login') {
  if (isset($_POST['u']) && isset($_POST['p']) && trim($_POST['u']) != '' && trim($_POST['p']) != '') {
    $maybe_user = user_authenticate($_POST['u'],$_POST['p']);
    if (is_array($maybe_user) && $maybe_user['user_id'] > 0) {
      if (_sec_log_in($maybe_user)) {
        _redirect('index.php');
      }
    } else {
      $Error = array("u" => $T['not_authenticated']);
    }
  }
}

require 'tpl/header.html.php';
require 'tpl/login.html.php';
require 'tpl/footer.html.php';

require '_footer.php';
