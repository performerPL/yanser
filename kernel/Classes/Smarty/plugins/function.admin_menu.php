<?php
function smarty_function_admin_menu($params, &$smarty)
{
  require(ROOT_PATH.'/ctrl/admin/modules.php');
  echo vAcl::getPMenu($_menu);
}
