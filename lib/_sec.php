<?php
if (!defined('_APP')) {
  exit;
}
if (defined('_LIB__SEC.PHP')) {
  return;
}
define('_LIB__SEC.PHP', 1);
session_start();

function _sec_logged()
{
  return isset($_SESSION['cms_logged_user']);
}

function _sec_log_in($tab)
{
  user_logged($tab['login']);
  $tab = user_get($tab['user_id']);
  $_SESSION['cms_logged_user'] = $tab;
  return true;
}

function _sec_log_out()
{
  user_delete_session();
  unset($_SESSION['cms_logged_user']);
  return true;
}

function _sec_user($key)
{
  return $_SESSION['cms_logged_user'][$key];
}

function _sec_hash($passwd)
{
  return md5($passwd);
}

function _sec_authorised($access)
{
  $level = _sec_user('access_level');
  return ($level & $access) > 0;
}

function _sec_authorise($access)
{
  if (!_sec_authorised($access)) {
    _redirect('login.php');
  }
}
