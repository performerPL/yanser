<?php
class Memcached extends Memcache{

	private $_hosts = array('localhost');

	/**
	 * Czas cachowania w sekundach.
	 *
	 * @var unknown_type
	 */
	static private $_cache_time = 3600;

    static private $m_objMem = NULL;
    
    static function getMem() {
        if (self::$m_objMem == NULL) {
            self::$m_objMem = new Memcache;
            // connect to the memcached on some 
                        //host __MEMHOST running it om __MEMPORT
            self::$m_objMem->connect('localhost', 11211) 
                        or die ("The memcached server");
        }
        return self::$m_objMem;
    }
	

	/**
	 * Pobiera zapytanie z cache.
	 * 
	 * @param unknown_type $sql
	 * @return unknown_type
	 */
	static public function getQuery($sql) {
		$key = md5($sql);
		// sprawdza czy user nie jest zalogowany do panelu admina oraz czy wpis juz istnieje
		if ( !isset($_SESSION['cms_logged_user']) && ($item = self::getMem()->get($key)) ) {
			return $item;
		}
		else {
			return null;
		}
	}

	
    /**
     * Dodaje zapytanie do cache.
     * 
     * @param unknown_type $sql
     * @param unknown_type $result
     * @return unknown_type
     */
	static public function setQuery($sql,$result) {
		$key = md5($sql);
		self::getMem()->set($key, $result, TRUE, self::$_cache_time);
		return $result;
	}

}