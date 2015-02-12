<?php
if (!defined('_APP')) {
  exit;
}
if (defined('_LIB_MENU.PHP')) {
  return;
}
define('_LIB_MENU.PHP', 1);

function contact_form_list()
{
  return _db_get('SELECT * FROM `' . DB_PREFIX . 'mod_contact_forms_type` ORDER BY form_type_name', 'form_type_id');
}

function contact_form_get($id)
{
  return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_contact_forms_type` WHERE form_type_id='.intval($id).' LIMIT 1');
}

function contact_form_update($tab)
{

  $t = array(
		'form_type_name'=>_db_string($tab['form_type_name']),
		'form_type_html'=>_db_string($tab['form_type_html']),
  );
  if ($tab['form_id'] > 0) {
    return _db_update('mod_contact_forms_type', $t, 'form_type_id=' . intval($tab['form_id']));
  } else {
    return _db_insert('mod_contact_forms_type', $t);
  }
}

function contact_form_delete($id)
{
  return _db_delete('mod_contact_forms_type','form_type_id=' . intval($id), 1);
}

function contact_form_validate($tab, $T)
{
  $res = array();

  if (trim($tab['form_type_name']) == '') {
    $res['form_type_name'] = $T['form_type_name_error'];
  }
  if (trim($tab['form_type_html']) == '') {
    $res['form_type_html'] = $T['form_type_html'];
  }
  return $res;
}
