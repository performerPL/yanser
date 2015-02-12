<?php
if (!defined('_APP')) {
	exit;
}
if (defined('_LIB__SYSTEM.PHP')) {
	return;
}
define('_LIB__SYSTEM.PHP', 1);

function _get_post($var_name, $default='')
{
	$res = isset($_GET[$var_name]) ? $_GET[$var_name] : $default;
	if ($res == $default) {
		$res = isset($_POST[$var_name]) ? $_POST[$var_name] : $default;
	}
	return $res;
}

function _is_email($s)
{
	return preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/' ,$s);
}

function _is_code($s)
{
	return preg_match(ADMIN_CODE_REGEX, $s);
}

function _redirect($url,$code = null)
{
	if (headers_sent()) {
		echo '<script type="text/javascript">location.href=\'' . $url . '\'</script>';
		exit;
	} else {
		if($code == 301) {
			header ('HTTP/1.1 301 Moved Permanently');
		}
		header('Location: ' . $url);
		exit;
	}
}

//przerzuca przekazaną tablicę + POST na podany adres

function _redirect_post($url, $add=array(), $remove=array())
{
	$tab = array_diff_assoc($_POST, $remove);
	$tab = array_merge($tab, $add);

	echo '<html><head></head><body><form action="'.$url.'" method="post" name="theForm">';
	foreach ($tab as $k=>$v) {
		echo '<input type="hidden" name="'.htmlspecialchars($k).'" value="'.htmlspecialchars($v).'" />';
	}
	echo '<input type="submit" style="border: 1px none; color: grey; background-color: white;" value="Click here to continue" /></form>';
	echo '<script type="text/javascript">document.theForm.submit();</script></body></html>';
	exit;
}

function _t($key, $html=false)
{
	global $T;
	$text = isset($T[$key]) ? $T[$key] : $key;
	echo $html ? $text : htmlspecialchars($text);
}

/*
 Dodaje tablicę tab do tablicy in - brane są tylko wartości, które istnieją już w in.

 @param in - wejsiowa tablica
 @param tab - tablica, która nadpisuje wartości podane w i
 @param skip - tablica z kluczami, która mają być pominięte w tej operacji

 @return wynikowa tablica
 */
function _merge($in, $tab, $skip=array())
{
	if (is_array($in) && is_array($tab)) {
		foreach ($in as $k => $v) {
			if (!in_array($k, $skip) && isset($tab[$k])) {
				$in[$k] = $tab[$k];
			}
		}
	}
	
	return $in;
}


/*
 Funckaj wyświetlająca błędy.

 @param s - błąd do wyświetlenia, DEBUG_LEVEL>0
 @param info - dodatkowa informacja, DEBUG_LEVEL>1
 */
function _debug($s, $info)
{
	if (defined('DEBUG') && intval(DEBUG) > 0) {
		echo trim($s) != '' ? '<br /><b>' . $s . '</b>' : '';
		if (intval(DEBUG) > 1) {
			echo '<br />' . $info;
		}
	}
}

