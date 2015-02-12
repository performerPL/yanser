<?php
if (!defined('_APP')) {
	exit;
}
if (defined('_LIB__DB.PHP')) {
	return;
}
define('_LIB__DB.PHP', 1);

function _db_decrypt($v)
{
	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($v), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

function _db_encrypt($v)
{
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $v, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function _db_query($q, $db=null,$debug = false)
{
	// czy wyświetlić zapytanie
	if($debug)
	echo $q;

	// ustawia kodowanie
	mysql_query('SET NAMES ' . $SQLNames = 'UTF8');
	$x = false;
	if (is_resource($db)) {
		$x = mysql_query($q,$db);
	} else {
		$x = mysql_query($q);
	}
	_debug(mysql_error(),$db);
	return $x;
}

function _db_sqlspecialchars($string, $db=null)
{
	if (is_resource($db)) {
		return mysql_real_escape_string($string, $db);
	} else {
		return mysql_real_escape_string($string);
	}
}
/**
 * Zwraca tablice z danymi.
 *
 * @param $query Zapytanie SQL
 * @param $index_key klucz w tabeli wyjsciowej[opcjonalnie]
 * @param $db Połączenie z bazą danych [opcjonalnie]
 * @param $useCache Czy użyć memcache.
 * @return unknown_type
 */
function _db_get($query, $index_key='', $db=null, $useCache = true)
{
	// MEMCACHED - odczyt
	if( ( USE_MEMCACHE == 1 ) && $useCache && ($res = Memcached::getQuery($query)) !== null) {
		return $res;
	}

	$res = array();
	$result = false;
	// ustawia kodowanie
	mysql_query('SET NAMES ' . $SQLNames = 'UTF8');
	if ($db == null) {
		$result = mysql_query($query);
		_debug(mysql_error(), $query);
	} else {
		$result = mysql_query($query, $db);
		_debug(mysql_error($db), $query);
	}
	if ($result) {
		if ($index_key != '') {
			while ($row= mysql_fetch_assoc($result)) {
				$res[$row[$index_key]] = $row;
			}
		} else {
			while ($row = mysql_fetch_assoc($result)) {
				$res[] = $row;
			}
		}
		mysql_free_result($result);
	}

	if(USE_MEMCACHE == 1) {
		// MEMCACHED - zapis
		Memcached::setQuery($query,$res);
	}
	return $res;
}

function _db_get_list($query, $index_key,$item_key,$first_row = null, $db=null)
{

	$res = array();
	if($first_row != null)
	$res[0] =$first_row;

	// ustawia kodowanie
	mysql_query('SET NAMES ' . $SQLNames = 'UTF8');
	$result = false;
	if ($db == null) {
		$result = mysql_query($query);
		_debug(mysql_error(), $query);
	} else {
		$result = mysql_query($query, $db);
		_debug(mysql_error($db), $query);
	}

	if ($result) {
		if ( ($index_key != '') && ($item_key != '') ) {
			while ($row= mysql_fetch_assoc($result)) {
				$res[$row[$index_key]] = $row[$item_key];
			}
		}
		mysql_free_result($result);
	}

	return $res;
}

/**
 * Pobiera jeden wiersz z bazy.
 *
 * @param $query Zapytanie
 * @param $db Baza [opcjonalnie]
 * @param $useCache Czy użyć cache [opcjonalnie]
 * @return unknown_type
 */
function _db_get_one($query, $db=null, $useCache = true)
{
	// MEMCACHED - odczyt
	if((USE_MEMCACHE == 1) && $useCache && ($res = Memcached::getQuery($query)) !== null) {
		return $res;
	}

	// ustawia kodowanie
	mysql_query('SET NAMES ' . $SQLNames = 'UTF8');
	$res = false;
	$result = false;
	if ($db == null) {
		$result = mysql_query($query);
		_debug(mysql_error(), $query);
	} else {
		$result = mysql_query($query, $db);
		_debug(mysql_error($db), $query);
	}
	if ($result) {
		if (mysql_num_rows($result) > 0) {
			$res = mysql_fetch_assoc($result);
		}
		mysql_free_result($result);
	}

    if(USE_MEMCACHE == 1) {
        // MEMCACHED - zapis
        Memcached::setQuery($query,$res);
    }

	return $res;
}

function _db_delete($tab, $key, $limit=0, $db=null)
{
	$query = 'DELETE FROM `'.DB_PREFIX.$tab.'`';
	if ($key != '') {
		$query .= ' WHERE ' . $key;
	}
	if ($limit > 0) {
		$query .= ' LIMIT ' . intval($limit);
	}
	if ($db != null)  {
		mysql_query($query, $db);
		_debug(mysql_error($db), $query);
		return mysql_errno($db) == 0;
	} else {
		mysql_query($query);
		//echo $query;
		_debug(mysql_error(), $query);
		return mysql_errno()== 0;
	}
}

function _db_replace($tab, $fields=array(), $db=null)
{
	// ustawia kodowanie
	mysql_query('SET NAMES ' . $SQLNames = 'UTF8');
	$query = 'REPLACE INTO `'.DB_PREFIX.$tab.'`';
	if (count($fields)>0) {
		$query .= '(`'.implode('`,`',array_keys($fields)).'`) ';
	}
	$query .= ' VALUES (';
	$q='';
	foreach($fields as $name=>$f) {
		$q .= ','.($f);
	}
	if($q!='') {
		$query .= substr($q,1);
	}
	$query .= ')';
	//	$result =false;
	if($db==null) {
		mysql_query($query);
		_debug(mysql_error(), $query);
		if(mysql_errno()>0) {
			return false;
		} else {
			return mysql_insert_id();
		}
	} else {
		mysql_query($query,$db);
		_debug(mysql_error($db),$query);
		if(mysql_errno($db)>0) {
			return false;
		} else {
			return mysql_insert_id($db);
		}
	}
}

function _db_insert($tab, $fields=array(),$db=null)
{
	// ustawia kodowanie
	mysql_query('SET NAMES ' . $SQLNames = 'UTF8');
	$query = 'INSERT INTO `'.DB_PREFIX.$tab.'`';
	if (count($fields)>0) {
		$query .= '(`'.implode('`,`',array_keys($fields)).'`) ';
	}
	$query .= ' VALUES (';
	$q='';
	foreach($fields as $name=>$f) {
		$q .= ','.($f);
	}
	if ($q!='') {
		$query .= substr($q,1);
	}
	$query .= ')';

	//	$result =false;
	if ($db==null) {
		mysql_query($query);
		_debug(mysql_error(), $query);
		if (mysql_errno()>0) {
			return false;
		} else {
			return mysql_insert_id();
		}
	} else {
		mysql_query($query,$db);
		_debug(mysql_error($db),$query);
		if (mysql_errno($db)>0) {
			return false;
		} else {
			return mysql_insert_id($db);
		}
	}
}

/**
 * Tymczasowa metoda zapisu do bazy nowego wiersza.
 * Poprzednia metoda nie dodaje '' do stringów.
 *
 * @param $tab
 * @param $fields
 * @param $db
 * @return unknown_type
 */
function _db_insert2($tab, $fields=array(),$db=null)
{
	// ustawia kodowanie
	mysql_query('SET NAMES ' . $SQLNames = 'UTF8');
	$query = 'INSERT INTO `'.DB_PREFIX.$tab.'`';
	if (count($fields)>0) {
		$query .= '(`'.implode('`,`',array_keys($fields)).'`) ';
	}
	$query .= ' VALUES (';
	$q='';
	foreach($fields as $name=>$f) {
		$q .= ",'". ($f) ."'";
	}
	if ($q!='') {
		$query .= substr($q,1);
	}
	$query .= ')';

	//  $result =false;
	if ($db==null) {
		mysql_query($query);
		_debug(mysql_error(), $query);
		if (mysql_errno()>0) {
			return false;
		} else {
			return mysql_insert_id();
		}
	} else {
		mysql_query($query,$db);
		_debug(mysql_error($db),$query);
		if (mysql_errno($db)>0) {
			return false;
		} else {
			return mysql_insert_id($db);
		}
	}
}

function _db_update($tab, $fields, $key = '', $limit = 1, $db = null)
{
	// ustawia kodowanie
	mysql_query('SET NAMES ' . $SQLNames = 'UTF8');
	$query = 'UPDATE `'.DB_PREFIX . $tab .  '` SET ';
	$q = '';
	foreach ($fields as $name=>$f) {
		$q .= ',`' . $name . '`=' . ($f);
	}
	if ($q!='') {
		$query .= substr($q,1);
	}
	if ($key!='') {
		$query .= ' WHERE '.$key;
	}
	if ($limit>0) {
		$query .= ' LIMIT '.intval($limit);
	}

	if ($db == null) {
		mysql_query($query);
		_debug(mysql_error(), $query);
		return (mysql_errno()==0);
	} else {
		mysql_query($query,$db);
		_debug(mysql_error($db),$query);
		return (mysql_errno($db)==0);
	}
}


function _db_reorder($db_table, $orderby, $oo, $no, $parent='', $parent_id=0, $db=null)
{
	// ustawia kodowanie
	mysql_query('SET NAMES ' . $SQLNames = 'UTF8');
	$parent_cond='';
	if(is_array($parent) && count($parent)>0) {
		for($i=0;$i<count($parent);++$i) {
			$parent_cond .= ' `'.$parent[$i].'`='.intval($parent_id[$i]).' AND ';
		}
	} else {
		if($parent!='') {
			$parent_cond .= ' `' . $parent . '`='.intval($parent_id).' AND ';
		}
	}
	$query ='';
	$no = intval($no);
	$oo = intval($oo);
	if($no > $oo) {
		$query = 'UPDATE `' . DB_PREFIX . $db_table . '` SET ';
		$query .= ' `' . $orderby . '` = (((`' . $orderby . '`-' . ($oo+1) . ')+' . (($no-$oo)+1) . ')%(' . (($no-$oo)+1) . '))+' . $oo;
		$query .= ' WHERE ';
		if($parent_cond!='') {
			$query .= $parent_cond;
		}
		$query .= '`' . $orderby . '`>=' . $oo . ' AND `' . $orderby . '`<=' . $no;
	} else {
		$query = 'UPDATE `'.DB_PREFIX . $db_table . '` SET ';
		$query .= ' `' . $orderby . '` = (((' . $orderby . '-' . $no . ')+1)%(' . (($oo-$no)+1) . '))+' . $no;
		$query .= ' WHERE ';
		if($parent_cond!='') {
			$query .= $parent_cond;
		}
		$query .= '`' . $orderby . '` >=' . $no . ' AND `' . $orderby . '`<=' . $oo;
	}
	//	$result =false;
	if($db==null) {
		mysql_query($query);
		_debug(mysql_error(), $query);
		return (mysql_errno()==0);
	} else {
		mysql_query($query,$db);
		_debug(mysql_error($db), $query);
		return (mysql_errno($db)==0);
	}
}

function _db_new_order($db_tab, $orderby, $parent='', $parent_id=0, $db=null)
{
	// ustawia kodowanie
	mysql_query('SET NAMES ' . $SQLNames = 'UTF8');
	$res = 1;

	$parent_cond='';
	if (is_array($parent) && count($parent) > 0) {
		for ($i=0; $i < count($parent); ++$i) {
			$parent_cond .= ' `' . $parent[$i] . '`=' . intval($parent_id[$i]);
			if ($i != count($parent) - 1) {
				$parent_cond .= ' AND ';
			}
		}
	} else {
		if ($parent != '') {
			$parent_cond .= ' `' . $parent . '`=' . intval($parent_id);
		}
	}

	$query = 'SELECT MAX(`' . $orderby . '`) as num FROM `' . DB_PREFIX . $db_tab . '`';
	if ($parent_cond != '') {
		$query .= ' WHERE ' . $parent_cond;
	}
	$result = false;
	if ($db == null) {
		$result = mysql_query($query);
		_debug(mysql_error(), $query);
		//return (mysql_errno()==0);
	} else {
		$result = mysql_query($query,$db);
		_debug(mysql_error($db), $query);
	}
	if ($result) {
		$row = mysql_fetch_assoc($result);
		mysql_free_result($result);
		$res = intval($row['num']) + 1;
	}
	return $res;
}

function _db_order_recompute($db_tab, $orderby, $key, $conditions)
{
	// ustawia kodowanie
	mysql_query('SET NAMES ' . $SQLNames = 'UTF8');
	if (!is_array($conditions)) {
		$conditions = array($conditions);
	}
	$sql = 'SELECT ' . $key . ' AS "a" FROM ' . DB_PREFIX . $db_tab . ' WHERE TRUE';
	foreach ($conditions as $field => $value) {
		$sql .= ' AND ' . $field . ' = \'' . _db_sqlspecialchars($value) . '\'';
	}
	$sql .= ' ORDER BY ' . $orderby;
	$res = _db_get($sql);
	$i = 1;
	foreach ($res as $value) {
		$sql = 'UPDATE ' . DB_PREFIX . $db_tab . ' SET ' . $orderby . ' = ' . $i++
		. ' WHERE ' . $key . ' = \'' . _db_sqlspecialchars($value['a']) . '\'';
		_db_query($sql);
	}
}

function _db_string($s='', $db=null)
{
	return '\'' . _db_sqlspecialchars($s, $db) . '\'';
}

function _db_int($x=0)
{
	return intval($x);
}

function _db_time($x='', $now=false, $db=null)
{
	if ($now) {
		return 'NOW()';
	} else {
		return '\'' . _db_sqlspecialchars($x, $db) . '\'';
	}
}

function _db_bool($x=false)
{
	return intval($x) > 0 ? '1' : '0';
}

function _db_dec($x = 0, $dec_sep = NF_DEC_SEP, $k_sep = NF_K_SEP)
{
	$val = doubleval(str_replace(array($dec_sep, $k_sep), array('.', ''), strval($x)));
	return $val;
}
function _db_pass($p) {
	return 'md5(' . _db_string($p) . ')';
}
