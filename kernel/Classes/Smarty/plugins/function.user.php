<?php

function smarty_function_user($params, &$smarty)
{
  if (empty($params['get'])) {
    $smarty->trigger_error("[user] unknow value for get param", E_USER_WARNING);
    return null;
  }
  return vClient::get($params['get']);
}