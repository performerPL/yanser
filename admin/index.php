<?php

require_once '_header.php';
require_once 'tpl/header.html.php';
require_once 'tpl/index.html.php';
require_once 'tpl/footer.html.php';
require_once '_footer.php';

if (substr($_SERVER['HTTP_HOST'], 0, 3) != 'www' && $_SERVER['HTTP_HOST'] != "localhost") {
  header('Location: http://www.'.$_SERVER['HTTP_HOST'] . '/admin/');
}